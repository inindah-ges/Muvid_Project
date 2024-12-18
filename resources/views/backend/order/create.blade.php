@extends('backend.template.main')

@section('title', 'Create New Order')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Create New Order</h6>
                            <div class="me-3">
                                <a href="{{ route('panel.order.index') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
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

                <div class="card-body">
                    <form action="{{ route('panel.order.store') }}" method="POST" id="orderForm" onsubmit="return validateForm()">
                        @csrf
                        <input type="hidden" name="subtotal" id="subtotalInput">
                        <input type="hidden" name="tax_amount" id="taxInput">
                        <input type="hidden" name="discount_amount" id="discountInput">
                        <input type="hidden" name="total_price" id="totalInput">

                        <!-- Product Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Select Products</h6>
                                        <div>
                                            <table class="text-end">
                                                <tr>
                                                    <td class="pe-3">Subtotal:</td>
                                                    <td><span id="subtotalAmount">Rp 0</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3">Tax ({{ $tax ? $tax->rate : 0 }}%):</td>
                                                    <td><span id="taxAmount">Rp 0</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3">Discount:</td>
                                                    <td><span id="discountAmount">Rp 0</span></td>
                                                </tr>
                                                <tr class="border-top">
                                                    <td class="pe-3"><strong>Total:</strong></td>
                                                    <td><strong id="totalAmount">Rp 0</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="orderItems">
                                            <!-- Item dinamis akan ditambahkan di sini -->
                                        </div>
                                        <button type="button" class="btn btn-secondary" onclick="addItem()">
                                            Add Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_type">Order Type</label>
                                    <select name="order_type" class="form-control border px-3" required>
                                        <option value="" hidden>---- Select Order Type ----</option>
                                        <option value="dine_in">Dine In</option>
                                        <option value="takeaway">Takeaway</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" class="form-control border px-3" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Order</button>
                                <a href="{{ route('panel.order.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let products = @json($products);
let tax = @json($tax);
let itemCount = 0;

function initializeSelect2(element) {
    $(element).select2({
        placeholder: 'Search product...',
        allowClear: true,
        width: '100%',
        data: products.map(product => ({
            id: product.id,
            text: `${product.name} - Rp ${product.price.toLocaleString('id')}`,
            price: product.price
        }))
    }).on('select2:select', function(e) {
        updatePrice(this);
    });
}

function calculateTotals() {
    const items = document.querySelectorAll('#orderItems .row');
    let subtotal = 0;
    let totalDiscount = 0;

    items.forEach(row => {
        const select = $(row).find('select');
        const quantity = parseInt($(row).find('input[type="number"]').val()) || 0;

        if (select.val()) {
            const selectedData = select.select2('data')[0];
            const price = selectedData.price;
            const itemSubtotal = price * quantity;
            subtotal += itemSubtotal;

            // Calculate discount (10% if quantity >= 10)
            if (quantity >= 10) {
                totalDiscount += itemSubtotal * 0.1;
            }

            // Update row subtotal
            $(row).find('.subtotal').text(`Rp ${itemSubtotal.toLocaleString('id')}`);
        }
    });

    // Calculate tax
    const taxAmount = tax ? (subtotal * (tax.rate / 100)) : 0;

    // Calculate final total
    const total = subtotal + taxAmount - totalDiscount;

    // Update displays with proper formatting
    $('#subtotalAmount').text(`Rp ${subtotal.toLocaleString('id')}`);
    $('#taxAmount').text(`Rp ${taxAmount.toLocaleString('id')}`);
    $('#discountAmount').text(`Rp ${totalDiscount.toLocaleString('id')}`);
    $('#totalAmount').text(`Rp ${total.toLocaleString('id')}`);
}

function addItem() {
    const container = document.getElementById('orderItems');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'row mb-3 align-items-center';
    itemDiv.innerHTML = `
        <div class="col-md-5">
            <select name="items[${itemCount}][product_id]" class="form-control border px-3" required>
                <option></option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="items[${itemCount}][quantity]"
                class="form-control border px-3" placeholder="Quantity"
                required min="1" value="1" onchange="calculateTotals()">
        </div>
        <div class="col-md-3">
            <span class="subtotal form-control border px-3">Rp 0</span>
        </div>
        <div class="col-md-1 mt-3">
            <button type="button" class="btn btn-danger" onclick="removeItem(this)">×</button>
        </div>
    `;
    container.appendChild(itemDiv);

    // Initialize Select2 pada select yang baru ditambahkan
    initializeSelect2(itemDiv.querySelector('select'));

    itemCount++;
}

function removeItem(button) {
    $(button).closest('.row').remove();
    calculateTotals();
}

function updatePrice(select) {
    calculateTotals();
}

function validateForm() {
    const items = document.querySelectorAll('select[name^="items["][name$="[product_id]"]');
    if (items.length === 0) {
        alert('Please add at least one product to the order');
        return false;
    }

    let isValid = true;
    items.forEach(select => {
        if (!select.value) {
            alert('Please select all products');
            isValid = false;
            return false;
        }
    });

    if (!isValid) return false;

    // Set values untuk hidden inputs
    const subtotal = document.getElementById('subtotalAmount').textContent.replace('Rp ', '').replace(/\./g, '');
    const taxAmount = document.getElementById('taxAmount').textContent.replace('Rp ', '').replace(/\./g, '');
    const discount = document.getElementById('discountAmount').textContent.replace('Rp ', '').replace(/\./g, '');
    const total = document.getElementById('totalAmount').textContent.replace('Rp ', '').replace(/\./g, '');

    document.getElementById('subtotalInput').value = subtotal;
    document.getElementById('taxInput').value = taxAmount;
    document.getElementById('discountInput').value = discount;
    document.getElementById('totalInput').value = total;

    return true;
}

// Initialize first item when document is ready
$(document).ready(function() {
    if (products && products.length > 0) {
        addItem();
    } else {
        console.error('Products data not loaded properly');
    }
});
</script>
@endpush
