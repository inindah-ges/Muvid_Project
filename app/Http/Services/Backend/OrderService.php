<?php

namespace App\Http\Services\Backend;

use App\Models\Backend\Order;
use App\Models\Backend\OrderDetail;
use App\Models\Backend\Product;
use App\Models\Backend\Tax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function store($userId, $orderData)
    {
        $userId = Auth::id();
        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            // Create order details first to calculate totals
            $orderDetails = [];
            foreach ($orderData['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemSubtotal = $product->price * $item['quantity'];

                // Calculate discount (10% if quantity >= 10)
                $discount = 0;
                if ($item['quantity'] >= 10) {
                    $discount = $itemSubtotal * 0.1;
                }

                // Get active tax
                $tax = Tax::first();
                $taxAmount = 0;
                if ($tax) {
                    // Tax is calculated after discount
                    $taxAmount = ($itemSubtotal - $discount) * ($tax->rate / 100);
                }

                $orderDetails[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                    'tax_id' => $tax ? $tax->id : null,
                    'discount' => $discount,
                ];

                $subtotal += $itemSubtotal;
                $totalTax += $taxAmount;
                $totalDiscount += $discount;
            }

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_amount' => 0,
                'order_type' => $orderData['order_type'],
                'notes' => $orderData['notes'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'total_price' => $subtotal + $totalTax - $totalDiscount,
            ]);

            // Create order details
            foreach ($orderDetails as $detail) {
                $order->orderDetails()->create($detail);
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getOrders($status = null, $paginate = null)
    {
        $userId = Auth::id();
        // Menggunakan Eloquent ORM dengan eager loading
        $orders = Order::with('user');

        if (Auth::user()->isPelanggan()) {
            $orders->where('user_id', $userId);
        }

        if ($status) {
            $orders->where('status', $status);
        }

        return $paginate ? $orders->latest()->paginate($paginate) : $orders->latest()->get();
    }

    public function getOrderByUuid($uuid)
    {
        $userId = Auth::id();
        // Menggunakan Eloquent firstOrFail
        return Order::with('user', 'orderDetails.product', )->whereUuid($uuid)->firstOrFail();
    }

    public function completeOrder($uuid)
    {
        $order = $this->getOrderByUuid($uuid);

        if (!$order->canBeCompleted()) {
            throw new \Exception('Order cannot be completed');
        }

        DB::transaction(function () use ($order) {
            // Create selling record
            $selling = $order->user->sellings()->create([
                'date' => now(),
                'total_price' => $order->total_price,
            ]);

            // Create selling details dengan tax dan discount
            foreach ($order->orderDetails as $detail) {
                // Ambil nilai dari order detail
                $selling->sellingDetails()->create([
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->price,
                    'tax_id' => $detail->tax_id,
                    'discount' => $detail->discount,
                    'subtotal' => $detail->subtotal,
                ]);
            }

            // Update order status
            $order->status = 'completed';
            $order->save();
        });

        return $order;
    }

    public function confirmOrder($uuid)
    {
        // Menggunakan Eloquent untuk update
        $order = $this->getOrderByUuid($uuid);

        if (!$order->canBeConfirmed()) {
            throw new \Exception('Order cannot be confirmed');
        }

        DB::transaction(function () use ($order) {
            $order->status = 'confirmed';
            $order->save();
        });

        return $order;
    }

    public function cancelOrder($uuid)
    {
        $order = $this->getOrderByUuid($uuid);

        if (!$order->canBeCancelled()) {
            throw new \Exception('Order cannot be cancelled');
        }

        DB::transaction(function () use ($order) {
            // If the order was confirmed, restore product stocks
            if ($order->status === 'confirmed') {
                foreach ($order->orderDetails as $detail) {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $product->stock += $detail->quantity;
                        $product->save();
                    }
                }
            }

            $order->status = 'cancelled';
            $order->save();
        });

        return $order;
    }

    public function processPayment($uuid, $paymentMethod, $paymentAmount = null)
    {
        $order = $this->getOrderByUuid($uuid);

        if (!$order->canBeProcessed()) {
            throw new \Exception('Order cannot be processed for payment');
        }

        if ($paymentMethod === 'cash' && $paymentAmount < $order->total_price) {
            throw new \Exception('Payment amount is insufficient');
        }

        DB::transaction(function () use ($order, $paymentMethod, $paymentAmount) {
            // Update order status
            $order->status = 'completed';
            $order->payment_status = 'paid';
            $order->payment_method = $paymentMethod;
            $order->payment_amount = $paymentAmount;
            $order->save();

            // Create selling
            $selling = $order->user->sellings()->create([
                'date' => now(),
                'total_price' => $this->calculateTotalWithTaxAndDiscount($order),
            ]);

            // Create selling details dengan tax dan discount
            foreach ($order->orderDetails as $detail) {
                // Ambil tax yang aktif
                $tax = Tax::where('name', 'PPN')->first();

                // Hitung discount
                $discount = $this->calculateDiscount($detail);

                $subtotal = $detail->price * $detail->quantity;
                $taxAmount = $tax ? ($subtotal * $tax->rate / 100) : 0;
                $finalSubtotal = $subtotal + $taxAmount - $discount;

                $selling->sellingDetails()->create([
                    'product_id' => $detail->product_id,
                    'tax_id' => $tax?->id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->price,
                    'discount' => $discount,
                    'subtotal' => $finalSubtotal,
                ]);

                // Kurangi stok produk
                $product = Product::find($detail->product_id);
                if ($product) {
                    if ($product->stock < $detail->quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }
                    $product->stock -= $detail->quantity;
                    $product->save();
                }
            }
        });

        return $order->fresh();
    }

    private function calculateTotalWithTaxAndDiscount($order)
    {
        $total = 0;
        foreach ($order->orderDetails as $detail) {
            $subtotal = $detail->price * $detail->quantity;

            // Hitung tax
            $tax = Tax::where('name', 'PPN')->first();
            $taxAmount = $tax ? ($subtotal * $tax->rate / 100) : 0;

            // Hitung discount
            $discount = $this->calculateDiscount($detail);

            $total += $subtotal + $taxAmount - $discount;
        }
        return $total;
    }

    private function calculateDiscount($orderDetail)
    {
        // Implementasi logika discount
        // Contoh: Discount berdasarkan quantity
        if ($orderDetail->quantity >= 10) {
            return $orderDetail->subtotal * 0.1; // 10% discount
        }
        return 0;
    }

    // Method untuk menambah order detail
    public function addOrderDetail($orderId, $productId, $quantity)
    {
        $order = Order::findOrFail($orderId);
        $product = Product::findOrFail($productId);

        $subtotal = $product->price * $quantity;

        $orderDetail = $order->orderDetails()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price,
            'subtotal' => $subtotal,
        ]);

        // Update total order
        $order->total_price = $order->orderDetails->sum('subtotal');
        $order->save();

        return $orderDetail;
    }

// Tambah method untuk update order detail
    public function updateOrderDetail($orderDetailId, $quantity)
    {
        $orderDetail = OrderDetail::findOrFail($orderDetailId);
        $subtotal = $orderDetail->price * $quantity;

        $orderDetail->update([
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ]);

        // Update total order
        $order = $orderDetail->order;
        $order->total_price = $order->orderDetails->sum('subtotal');
        $order->save();

        return $orderDetail;
    }

// Tambah method untuk delete order detail
    public function deleteOrderDetail($orderDetailId)
    {
        $orderDetail = OrderDetail::findOrFail($orderDetailId);
        $order = $orderDetail->order;

        $orderDetail->delete();

        // Update total order
        $order->total_price = $order->orderDetails->sum('subtotal');
        $order->save();

        return true;
    }
}
