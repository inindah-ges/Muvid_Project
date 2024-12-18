<?php

namespace App\Http\Controllers;

use App\Models\Backend\Order;
use App\Models\Backend\Product;
use App\Models\Backend\Tax;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use stdClass;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_3DS', true);

        Config::$paymentIdempotencyKey = true;
    }

    public function checkout()
    {
        try {
            $pendingOrder = Order::where('user_id', Auth::id())
                ->where('payment_status', 'unpaid')
                ->where('status', 'pending')
                ->first();

            if ($pendingOrder) {
                $pendingOrder->update([
                    'status' => 'cancelled',
                    'payment_status' => 'unpaid',
                ]);
            }

            $cartItems = Cart::where('user_id', Auth::id())->get();

            // Cek stok sebelum membuat order
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok tidak mencukupi untuk produk: {$item->product->name}");
                }
            }

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
            }

            $tax = Tax::where('name', 'PPN')->first();

            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $discount = $cartItems->sum(function ($item) {
                $itemSubtotal = $item->product->price * $item->quantity;
                return $item->quantity >= 10 ? $itemSubtotal * 0.1 : 0;
            });

            $taxAmount = ($subtotal - $discount) * ($tax ? $tax->rate / 100 : 0);
            $total = $subtotal - $discount + $taxAmount;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'payment_amount' => $total,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'order_type' => 'dine_in',
            ]);

            foreach ($cartItems as $item) {
                $itemSubtotal = $item->product->price * $item->quantity;
                $itemDiscount = $item->quantity >= 10 ? $itemSubtotal * 0.1 : 0;

                $order->orderDetails()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $itemSubtotal,
                    'tax_id' => $tax ? $tax->id : null,
                    'discount' => $itemDiscount,
                ]);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) round($total),
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'enabled_payments' => ['shopeepay', 'qris'],
                'callbacks' => [
                    'finish' => route('payment.finish', $order->uuid),
                    'error' => route('payment.error', $order->uuid),
                    'pending' => route('payment.pending', $order->uuid),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return view('checkout', compact('snapToken', 'cartItems', 'order', 'tax'));

        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Error membuat pesanan: ' . $e->getMessage());
        }
    }

    public function checkStatus($orderId)
    {
        try {
            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            /** @var stdClass $status */
            $status = Transaction::status($orderId);

            $this->updateOrderStatus(
                $order,
                $status->transaction_status,
                $status->payment_type,
                $status->fraud_status ?? null,
                $status->gross_amount
            );

            return response()->json([
                'success' => true,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function finish($uuid)
    {
        try {
            $order = Order::where('uuid', $uuid)->firstOrFail();

            /** @var stdClass $status */
            $status = Transaction::status($order->order_number);

            $this->updateOrderStatus(
                $order,
                $status->transaction_status,
                $status->payment_type,
                $status->fraud_status ?? null,
                $status->gross_amount
            );

            if ($order->payment_status === 'paid') {
                Cart::where('user_id', Auth::id())->delete();
                return redirect()->route('frontend.home')
                    ->with('success', 'Pembayaran berhasil! Terima kasih telah melakukan pemesanan.');
            }

            return redirect()->route('frontend.home')
                ->with('info', 'Menunggu konfirmasi pembayaran');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran');
        }
    }

    public function error($uuid)
    {
        return redirect()->route('cart.index')
            ->with('error', 'Terjadi kesalahan dalam proses pembayaran');
    }

    public function pending($uuid)
    {
        return redirect()->route('frontend.home')
            ->with('info', 'Menunggu pembayaran. Silakan selesaikan pembayaran Anda');
    }

    private function updateOrderStatus($order, string $transaction, string $type, ?string $fraudStatus, float $amount)
    {
        switch ($transaction) {
            case 'capture':
            case 'settlement':
                DB::transaction(function () use ($order, $type, $amount) {
                    $order->status = 'confirmed';
                    $order->payment_status = 'paid';
                    $order->payment_method = $this->mapPaymentMethod($type);
                    $order->payment_amount = $amount;

                    // Kurangi stok produk setelah pembayaran berhasil
                    foreach ($order->orderDetails as $detail) {
                        $product = Product::find($detail->product_id);
                        if ($product) {
                            if ($product->stock < $detail->quantity) {
                                throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
                            }
                            $product->stock -= $detail->quantity;
                            $product->save();
                        }
                    }

                    $order->save();
                });
                break;

            case 'pending':
                $order->status = 'pending';
                $order->payment_method = $this->mapPaymentMethod($type);
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $order->status = 'cancelled';
                break;
        }

        $order->save();
    }

    private function mapPaymentMethod(string $type): string
    {
        return match ($type) {
            // E-Wallet
            // 'gopay' => 'gopay',
            'shopeepay' => 'shopeepay',
            'qris' => 'qris',

            // Credit/Debit
            // 'credit_card' => 'credit_card',

            // Cardless Credit
            // 'gopay_later' => 'gopay_later',
            // 'shopeepay_later' => 'shopeepay_later',
            // 'akulaku' => 'akulaku',
            // 'kredivo' => 'kredivo',

            // Convenience Store
            // 'indomaret' => 'indomaret',
            // 'alfamart' => 'alfamart',

            // Bank Transfer
            'bank_transfer' => match ($this->getBankType($type)) {
            // 'bca' => 'bca_va',
            // 'bni' => 'bni_va',
            // 'bri' => 'bri_va',
            // 'mandiri' => 'mandiri_va',
            // 'permata' => 'permata_va',
            // 'cimb' => 'cimb_va',
            // 'bsi' => 'bsi_va',
            // 'danamon' => 'danamon_va',
                default => $type
            },
            default => $type
        };
    }

    private function getBankType(string $type): string
    {
        try {
            /** @var stdClass $status */
            $status = Transaction::status($type);
            return $status->va_numbers[0]->bank ?? $type;
        } catch (\Exception) {
            return $type;
        }
    }
}
