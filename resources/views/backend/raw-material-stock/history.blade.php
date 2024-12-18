@extends('backend.template.main')

@section('title', 'Stock History')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Stock Movement History</h6>
                            <div class="me-3">
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('panel.raw-material-stock.index') }}" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($filters)
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <span class="text-sm">Active Filters:</span>
                        @if($filters['raw_material_id'])
                            <span class="badge bg-gradient-info ms-2">
                                Material: {{ $rawMaterials->find($filters['raw_material_id'])->name }}
                            </span>
                        @endif
                        @if($filters['type'])
                            <span class="badge bg-gradient-info ms-2">
                                Type: {{ ucfirst($filters['type']) }}
                            </span>
                        @endif
                        @if($filters['date_from'])
                            <span class="badge bg-gradient-info ms-2">
                                From: {{ $filters['date_from'] }}
                            </span>
                        @endif
                        @if($filters['date_to'])
                            <span class="badge bg-gradient-info ms-2">
                                To: {{ $filters['date_to'] }}
                            </span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('panel.raw-material-stock.history') }}" class="btn btn-sm btn-outline-secondary mt-3">
                            Clear Filters
                        </a>
                    </div>
                </div>
                @endif

                <div class="card-body px-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 50px">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notes</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Updated By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                <tr>
                                    <td class="text-center">
                                        {{ ($stocks->currentPage() - 1) * $stocks->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $stock->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $stock->rawMaterial->name }}</td>
                                    <td>{{ $stock->rawMaterial->category->name }}</td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $stock->type === 'in' ? 'success' : 'danger' }}">
                                            {{ ucfirst($stock->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $stock->quantity }} {{ $stock->rawMaterial->unit }}</td>
                                    <td>{{ $stock->notes ?: '-' }}</td>
                                    <td>{{ $stock->user->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No stock movements found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 mt-3">
                        {{ $stocks->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Stock History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('panel.raw-material-stock.history') }}" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="raw_material_id">Material</label>
                            <select name="raw_material_id" id="raw_material_id" class="form-select border px-3">
                                <option value="">All Materials</option>
                                @foreach($rawMaterials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ isset($filters['raw_material_id']) && $filters['raw_material_id'] == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }} ({{ $material->category->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="type">Movement Type</label>
                            <select name="type" id="type" class="form-select border px-3">
                                <option value="">All Types</option>
                                <option value="in" {{ isset($filters['type']) && $filters['type'] == 'in' ? 'selected' : '' }}>Stock In</option>
                                <option value="out" {{ isset($filters['type']) && $filters['type'] == 'out' ? 'selected' : '' }}>Stock Out</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_from">Date From</label>
                            <input type="date" class="form-control border px-3" name="date_from" id="date_from"
                                value="{{ $filters['date_from'] ?? '' }}" max="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_to">Date To</label>
                            <input type="date" class="form-control border px-3" name="date_to" id="date_to"
                                value="{{ $filters['date_to'] ?? '' }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .table tbody td {
        padding: 1rem 0.5rem !important;
    }
</style>
@endpush
