@extends('backend.template.main')

@section('title', 'Usage Tracking')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Raw Material Usage</h6>
                            <div class="me-3">
                                @if (auth()->user()->hasRole('pegawai'))
                                <a href="{{ route('panel.raw-material-usage.create') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-plus me-1"></i> Record Usage
                                </a>
                                @endif
                                <button type="button" class="btn btn-sm btn-light ms-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('panel.raw-material-usage.report') }}" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-file-alt me-1"></i> Report
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

                @if($filters)
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <span class="text-sm">Active Filters:</span>
                        @if($filters['raw_material_id'] ?? null)
                            <span class="badge bg-gradient-info ms-2">
                                Material: {{ $rawMaterials->find($filters['raw_material_id'])->name }}
                            </span>
                        @endif
                        @if($filters['date_from'] ?? null)
                            <span class="badge bg-gradient-info ms-2">
                                From: {{ $filters['date_from'] }}
                            </span>
                        @endif
                        @if($filters['date_to'] ?? null)
                            <span class="badge bg-gradient-info ms-2">
                                To: {{ $filters['date_to'] }}
                            </span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('panel.raw-material-usage.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                            Clear Filters
                        </a>
                    </div>
                </div>
                @endif

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity Used</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usages as $usage)
                                <tr>
                                    <td>
                                        {{ ($usages->currentPage() - 1) * $usages->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $usage->date->format('d M Y') }}</td>
                                    <td>{{ $usage->rawMaterial->name }}</td>
                                    <td>{{ $usage->rawMaterial->category->name }}</td>
                                    <td>{{ $usage->quantity_used }} {{ $usage->rawMaterial->unit }}</td>
                                    <td>{{ $usage->user->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No usage records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 mt-3">
                        {{ $usages->withQueryString()->links() }}
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
                <h5 class="modal-title">Filter Usage History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('panel.raw-material-usage.index') }}" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="raw_material_id">Material</label>
                            <select name="raw_material_id" id="raw_material_id" class="form-select border px-3">
                                <option value="">All Materials</option>
                                @foreach($rawMaterials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ ($filters['raw_material_id'] ?? '') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }} ({{ $material->category->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control border px-3" name="date_from" id="date_from"
                                    value="{{ $filters['date_from'] ?? '' }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control border px-3" name="date_to" id="date_to"
                                    value="{{ $filters['date_to'] ?? '' }}" max="{{ date('Y-m-d') }}">
                            </div>
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
