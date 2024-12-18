@extends('backend.template.main')

@section('title', 'Order Detail')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Order #{{ $order->order_number }}</h6>
                            <div class="me-3">
                                <a href="{{ route('panel.order.index') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                                <button onclick="printOrder()" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 text-white" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 text-white" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card-body px-4 pt-4">
                    {{-- Status Bar --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between position-relative align-items-center">
                                <div class="position-absolute" style="height: 2px; background-color: #e9ecef; width: 100%; top: 50%; transform: translateY(-50%);"></div>
                                <div class="d-flex justify-content-between position-relative" style="width: 100%;">
                                    <div class="px-3 py-1 rounded bg-white text-center">
                                        <div class="rounded-circle mb-1 {{ $order->status != 'pending' ? 'bg-success' : 'bg-warning' }} text-white" style="width: 30px; height: 30px; line-height: 30px;">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <small>Pending</small>
                                    </div>
                                    <div class="px-3 py-1 rounded bg-white text-center">
                                        <div class="rounded-circle mb-1 {{ $order->status == 'confirmed' || $order->status == 'completed' ? 'bg-success' : 'bg-secondary' }} text-white" style="width: 30px; height: 30px; line-height: 30px;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <small>Confirmed</small>
                                    </div>
                                    <div class="px-3 py-1 rounded bg-white text-center">
                                        <div class="rounded-circle mb-1 {{ $order->status == 'completed' ? 'bg-success' : 'bg-secondary' }} text-white" style="width: 30px; height: 30px; line-height: 30px;">
                                            <i class="fas fa-flag-checkered"></i>
                                        </div>
                                        <small>Completed</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order & Customer Information --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Order Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="150">Order Number</th>
                                                <td>{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-{{ $order->status_badge }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Order Type</th>
                                                <td>
                                                    @if ($order->order_type === 'takeaway')
                                                        <span class="badge badge-sm bg-gradient-secondary">Bawa Pulang</span>
                                                    @elseif ($order->order_type === 'dine_in')
                                                        <span class="badge badge-sm bg-gradient-secondary">Makan Ditempat</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Payment Status</th>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-{{ $order->payment_status_badge }}">
                                                        {{ ucfirst($order->payment_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @if($order->payment_status === 'paid')
                                            <tr>
                                                <th>Payment Method</th>
                                                <td>{{ ucfirst($order->payment_method) }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="150">Name</th>
                                                <td>{{ $order->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $order->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Order Count</th>
                                                <td>{{ $order->user->orders()->count() }} orders</td>
                                            </tr>
                                            <tr>
                                                <th>Member Since</th>
                                                <td>{{ $order->user->created_at->format('d M Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Order Items</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Item</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subtotal</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tax</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Discount</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderDetails as $detail)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $detail->product->name }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $detail->product->category->name }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                                    <td>{{ $detail->quantity }}</td>
                                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($detail->tax)
                                                            @php
                                                                $afterDiscount = $detail->subtotal - ($detail->discount ?? 0);
                                                                $taxAmount = $afterDiscount * $detail->tax->rate / 100;
                                                            @endphp
                                                            Rp {{ number_format($taxAmount, 0, ',', '.') }}
                                                            <small class="text-muted">({{ $detail->tax->rate }}%)</small>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($detail->discount)
                                                            Rp {{ number_format($detail->discount, 0, ',', '.') }}
                                                            <small class="text-muted">(10%)</small>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="border-top">
                                                <tr>
                                                    <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                                                    <td colspan="6"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-end">Tax Total:</td>
                                                    <td colspan="6">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-end">Discount Total:</td>
                                                    <td colspan="6">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-end"><strong>Grand Total:</strong></td>
                                                    <td colspan="6"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($order->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Notes</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0 text-break">{{ $order->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                @if(auth()->user()->isAdmin() && $order->canBeConfirmed())
                                <form action="{{ route('panel.order.confirm', $order->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Are you sure want to confirm this order?')">
                                        <i class="fas fa-check me-1"></i> Confirm Order
                                    </button>
                                </form>
                                @endif

                                @if(auth()->user()->isAdmin() && $order->canBeCancelled())
                                <form action="{{ route('panel.order.cancel', $order->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure want to cancel this order?')">
                                        <i class="fas fa-times me-1"></i> Cancel Order
                                    </button>
                                </form>
                                @endif

                                @if($order->canBeProcessed())
                                <button type="button" class="btn btn-primary"
                                        onclick="showPaymentModal('{{ $order->uuid }}')">
                                    <i class="fas fa-money-bill me-1"></i> Process Payment
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Payment Modal --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Order Summary -->
                    <div class="mb-4">
                        <h6>Order Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150">Subtotal</td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-end">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Total Amount</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-select border px-3" name="payment_method" id="paymentMethod" required>
                            <option value="" hidden>---- Select Payment Method ----</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>

                    <!-- Cash Payment Fields -->
                    <div id="cashPaymentFields" class="mt-3" style="display: none;">
                        <div class="form-group">
                            <label for="paymentAmount">Payment Amount</label>
                            <input type="number" class="form-control border px-3" id="paymentAmount" name="payment_amount"
                                min="{{ $order->total_price }}">
                            <div class="form-text text-muted">Minimum amount: Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between">
                                    <span>Change:</span>
                                    <span id="changeAmount" class="text-success">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitPayment" disabled>Process Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function showPaymentModal(uuid) {
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        const form = document.getElementById('paymentForm');

        // Reset form
        form.reset();
        document.getElementById('cashPaymentFields').style.display = 'none';
        document.getElementById('submitPayment').disabled = true;
        document.getElementById('changeAmount').textContent = 'Rp 0';

        // Set form action
        form.action = `/panel/order/${uuid}/process-payment`;

        modal.show();
    }

    document.getElementById('paymentMethod').addEventListener('change', function() {
        const cashFields = document.getElementById('cashPaymentFields');
        const submitBtn = document.getElementById('submitPayment');

        if (this.value === 'cash') {
            cashFields.style.display = 'block';
            submitBtn.disabled = true; // Disable until valid amount entered
        } else if (this.value) {
            cashFields.style.display = 'none';
            submitBtn.disabled = false; // Enable for non-cash payments
        } else {
            cashFields.style.display = 'none';
            submitBtn.disabled = true;
        }
    });

    document.getElementById('paymentAmount').addEventListener('input', function() {
        const total = {{ $order->total_price }};
        const amount = Number(this.value);
        const submitBtn = document.getElementById('submitPayment');
        const changeAmount = document.getElementById('changeAmount');

        if (amount >= total) {
            const change = amount - total;
            changeAmount.textContent = `Rp ${change.toLocaleString('id')}`;
            changeAmount.classList.remove('text-danger');
            changeAmount.classList.add('text-success');
            submitBtn.disabled = false;
        } else {
            changeAmount.textContent = 'Payment amount is insufficient!';
            changeAmount.classList.remove('text-success');
            changeAmount.classList.add('text-danger');
            submitBtn.disabled = true;
        }
    });

    // Validate form before submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const paymentMethod = this.querySelector('[name="payment_method"]').value;
        const total = {{ $order->total_price }};

        if (paymentMethod === 'cash') {
            const amount = Number(this.querySelector('[name="payment_amount"]').value);
            if (amount < total) {
                e.preventDefault();
                alert('Payment amount is insufficient!');
                return false;
            }
        }
    });

    function printOrder() {
        window.print();
    }
</script>
@endpush
