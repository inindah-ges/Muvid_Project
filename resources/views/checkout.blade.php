@extends('frontend.template.main')

@section('content')
<div class="container bg-light py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white m-3 pb-3">
                    <h4 class="mb-0">Ringkasan Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk/Menu</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Tax ({{ $tax ? $tax->rate : 0 }}%)</th>
                                    <th class="text-end">Diskon</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    @php
                                        $subtotal = $item->product->price * $item->quantity;
                                        $discount = $item->quantity >= 10 ? $subtotal * 0.1 : 0;
                                        $taxAmount = ($subtotal - $discount) * ($tax ? $tax->rate / 100 : 0);
                                        $total = $subtotal - $discount + $taxAmount;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                            </div>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($taxAmount, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            @if($discount > 0)
                                                Rp {{ number_format($discount, 0, ',', '.') }}
                                                <small class="text-muted">(10%)</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end">Tax Total:</td>
                                    <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end">Diskon Total:</td>
                                    <td class="text-end">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url('/cart') }}" class="btn btn-secondary">Kembali ke Keranjang</a>
                        <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
{{-- <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script> --}}
<script type="text/javascript">
let isCheckingStatus = false;

document.getElementById('pay-button').onclick = function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            handlePaymentComplete(result, 'success');
        },
        onPending: function(result) {
            handlePaymentComplete(result, 'pending');
            startStatusCheck('{{ $order->order_number }}');
        },
        onError: function(result) {
            handlePaymentComplete(result, 'error');
        },
        onClose: function() {
            if (isCheckingStatus) {
                Swal.fire({
                    title: 'Memproses Pembayaran',
                    text: 'Mohon tunggu sebentar...',
                    icon: 'info',
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    title: 'Pembayaran Dibatalkan',
                    text: 'Anda menutup halaman pembayaran',
                    icon: 'warning'
                }).then(() => {
                    window.location.href = '/cart';
                });
            }
        }
    });
};

function startStatusCheck(orderId) {
    isCheckingStatus = true;
    let attempts = 0;
    const maxAttempts = 12; // 2 menit (12 x 10 detik)
    
    const checkInterval = setInterval(async () => {
        try {
            const response = await fetch(`/check-payment-status/${orderId}`);
            const data = await response.json();
            
            if (data.payment_status === 'paid') {
                clearInterval(checkInterval);
                isCheckingStatus = false;
                window.location.href = '/payment/finish/{{ $order->uuid }}';
            }
            
            attempts++;
            if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                isCheckingStatus = false;
                Swal.fire({
                    title: 'Status Pembayaran',
                    text: 'Silakan cek status pesanan Anda di halaman riwayat pesanan',
                    icon: 'info'
                }).then(() => {
                    window.location.href = '/';
                });
            }
        } catch (error) {
            console.error('Error checking payment status:', error);
        }
    }, 10000); // Check setiap 10 detik
}

function handlePaymentComplete(result, status) {
    console.log(`Payment ${status}:`, result);
    if (status === 'success') {
        window.location.href = '/payment/finish/{{ $order->uuid }}';
    }
}
</script>
@endsection
