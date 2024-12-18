@extends('backend.template.main')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Order List</h6>
                            <div class="me-3">
                                <div class="btn-group">
                                    <a href="{{ route('panel.order.index') }}"
                                        class="btn btn-sm btn-light {{ !$status ? 'active' : '' }}">All</a>
                                    <a href="{{ route('panel.order.index', ['status' => 'pending']) }}"
                                        class="btn btn-sm btn-light {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
                                    <a href="{{ route('panel.order.index', ['status' => 'confirmed']) }}"
                                        class="btn btn-sm btn-light {{ $status === 'confirmed' ? 'active' : '' }}">Confirmed</a>
                                    <a href="{{ route('panel.order.index', ['status' => 'completed']) }}"
                                        class="btn btn-sm btn-light {{ $status === 'completed' ? 'active' : '' }}">Completed</a>
                                    <a href="{{ route('panel.order.index', ['status' => 'cancelled']) }}"
                                        class="btn btn-sm btn-light {{ $status === 'completed' ? 'active' : '' }}">Cancelled</a>

                                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pelanggan'))
                                    <div class="ms-2">
                                        <a href="{{ route('panel.order.create') }}" class="btn btn-sm btn-dark">
                                            <i class="fas fa-plus me-1"></i> New Order
                                        </a>
                                    </div>
                                    @endif

                                </div>
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

                <div class="card-body px-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order #</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4">{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ ucfirst($order->order_type) }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $order->status_badge }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $order->payment_status_badge }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('panel.order.show', $order->uuid) }}"
                                                class="btn btn-info btn-md">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(auth()->user()->isAdmin() && $order->canBeConfirmed())
                                            <form action="{{ route('panel.order.confirm', $order->uuid) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-md"
                                                        onclick="return confirm('Are you sure want to confirm this order?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif

                                            @if($order->canBeCompleted())
                                                <form action="{{ route('panel.order.complete', $order->uuid) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-md"
                                                            onclick="return confirm('Are you sure want to complete this order?')">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->isAdmin() && $order->canBeCancelled())
                                            <form action="{{ route('panel.order.cancel', $order->uuid) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-md"
                                                        onclick="return confirm('Are you sure want to cancel this order?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif

                                            @if($order->canBeProcessed())
                                                <button type="button" class="btn btn-primary btn-md"
                                                        onclick="showPaymentModal('{{ $order->uuid }}', {{ $order->total_price }})">
                                                    <i class="fas fa-money-bill"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 justify-content-center" style="margin-left: 20px; margin-right: 20px;">
                        {{ $orders->links() }}
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
                    <!-- Order Total -->
                    <div class="mb-4">
                        <h6>Order Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong id="modalTotalAmount">Rp 0</strong></td>
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
                            <input type="number" class="form-control border px-3" id="paymentAmount" name="payment_amount" min="0" step="1000">
                            <div class="mt-3">
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
function showPaymentModal(uuid, totalAmount) {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const form = document.getElementById('paymentForm');
    const totalAmountEl = document.getElementById('modalTotalAmount');

    // Reset form
    form.reset();
    document.getElementById('cashPaymentFields').style.display = 'none';
    document.getElementById('submitPayment').disabled = true;

    // Set action and total
    form.action = `/panel/order/${uuid}/process-payment`;
    totalAmountEl.textContent = `Rp ${Number(totalAmount).toLocaleString('id')}`;
    form.dataset.total = totalAmount;

    modal.show();
}

document.getElementById('paymentMethod').addEventListener('change', function() {
    const cashFields = document.getElementById('cashPaymentFields');
    const submitBtn = document.getElementById('submitPayment');
    const total = Number(this.form.dataset.total);

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
    const total = Number(this.form.dataset.total);
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
    const total = Number(this.dataset.total);

    if (paymentMethod === 'cash') {
        const amount = Number(this.querySelector('[name="payment_amount"]').value);
        if (amount < total) {
            e.preventDefault();
            alert('Payment amount is insufficient!');
            return false;
        }
    }
});
</script>
@endpush
