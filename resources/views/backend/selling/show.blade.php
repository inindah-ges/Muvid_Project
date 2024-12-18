@extends('backend.template.main')

@section('title', 'Sale Detail')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Sale Detail - {{ $selling->invoice }}</h6>
                            <div class="me-3">
                                <a href="{{ route('panel.selling.index') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                                <a href="{{ route('panel.selling.generate-invoice', $selling->uuid) }}"
                                    class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-file-pdf me-1"></i> Download Invoice
                                </a>
                                <button type="button" onclick="printPage()" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pt-4">
                    {{-- Company Information --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1">Kanal Social Space</h4>
                                    <p class="mb-1"> Jl. Wijaya Kusuma No.6, Makassar</p>
                                    <p class="mb-1">Phone: 0852-5602-9021</p>
                                    <p class="mb-0">Email: info@kanalsocialspace.com</p>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-1">INVOICE #{{ $selling->invoice }}</h5>
                                    <p class="mb-1">Date: {{ $selling->created_at->format('d M Y') }}</p>
                                    <p class="mb-0">Time: {{ $selling->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Customer & Sale Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Name</th>
                                            <td>{{ $selling->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $selling->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Member Since</th>
                                            <td>{{ $selling->user->created_at->format('d M Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Sale Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Invoice Number</th>
                                            <td>{{ $selling->invoice }}</td>
                                        </tr>
                                        <tr>
                                            <th>Transaction Date</th>
                                            <td>{{ $selling->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Items</th>
                                            <td>{{ $selling->sellingDetails->count() }} items</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sale Items --}}
                    <div class="card shadow-none border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Sale Items</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Item</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Unit Price</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Discount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tax</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = 0;
                                            $totalDiscount = 0;
                                            $totalTax = 0;
                                        @endphp
                                        @foreach($selling->sellingDetails as $detail)
                                        @php
                                            $itemSubtotal = $detail->quantity * $detail->unit_price;
                                            $itemDiscount = $detail->discount ?? 0;
                                            $itemTax = $detail->tax ? ($itemSubtotal - $itemDiscount) * ($detail->tax->rate / 100) : 0;
                                            $subtotal += $itemSubtotal;
                                            $totalDiscount += $itemDiscount;
                                            $totalTax += $itemTax;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    @if($detail->product->image)
                                                    <div class="me-3">
                                                        <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                            class="avatar avatar-sm rounded-circle"
                                                            alt="{{ $detail->product->name }}">
                                                    </div>
                                                    @endif
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $detail->product->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $detail->product->category->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>
                                                @if($detail->discount)
                                                    Rp {{ number_format($detail->discount, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($detail->tax)
                                                    {{ $detail->tax->rate }}% (Rp {{ number_format($itemTax, 0, ',', '.') }})
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($itemSubtotal - $itemDiscount + $itemTax, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Total Discount:</strong></td>
                                            <td>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Total Tax:</strong></td>
                                            <td>Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                            <td><strong>Rp {{ number_format($selling->total_price, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Notes --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-none border">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-3">Terms & Conditions:</h6>
                                            <p class="mb-0 text-sm">
                                                1. All prices are in Indonesian Rupiah (IDR)<br>
                                                2. This is a computer generated invoice<br>
                                                3. Thank you for your business!
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="mt-4">
                                                <p class="mb-1">Authorized By</p>
                                                <br>
                                                <p class="mb-0">_________________</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function printPage() {
        window.print();
    }
</script>
@endpush

@push('css')
<style>
    @media print {
        .sidenav,
        .navbar,
        .btn,
        footer {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        .card {
            box-shadow: none !important;
        }
    }
</style>
@endpush
