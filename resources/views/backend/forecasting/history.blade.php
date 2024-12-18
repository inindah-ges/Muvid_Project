@extends('backend.template.main')

@section('title', 'Forecast History')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Forecast History</h6>
                            <div class="me-3">
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('panel.forecasting.index') }}" class="btn btn-sm btn-light ms-2">
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
                        <a href="{{ route('panel.forecasting.history') }}" class="btn btn-sm btn-outline-secondary mt-3">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actual Usage</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Predicted</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Method</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Error Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($forecasts as $index => $forecast)
                                <tr>
                                    <td>{{ $forecasts->firstItem() + $index }}</td>
                                    <td>{{ $forecast->date->format('d M Y') }}</td>
                                    <td>{{ $forecast->rawMaterial->name }}</td>
                                    <td>{{ $forecast->rawMaterial->category->name }}</td>
                                    <td>
                                        @if(is_null($forecast->actual_usage))
                                            <a href="{{ route('panel.forecasting.edit-actual', $forecast->uuid) }}"
                                            class="btn btn-sm btn-info m-3">
                                                Update Actual
                                            </a>
                                        @else
                                            {{ number_format($forecast->actual_usage, 1) }} {{ $forecast->rawMaterial->unit }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($forecast->predicted_amount, 1) }} {{ $forecast->rawMaterial->unit }}</td>
                                    <td>{{ $forecast->forecasting_method }}</td>
                                    <td>
                                        <span class="badge bg-{{ $forecast->error_rate <= 10 ? 'success' : ($forecast->error_rate <= 20 ? 'warning' : 'danger') }}">
                                            {{ number_format($forecast->error_rate, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">No forecast history found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 mt-3">
                        {{ $forecasts->withQueryString()->links() }}
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
                <h5 class="modal-title">Filter History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('panel.forecasting.history') }}" method="GET">
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

@push('js')
<script>
document.getElementById('date_from').addEventListener('change', function() {
    document.getElementById('date_to').min = this.value;
});

document.getElementById('date_to').addEventListener('change', function() {
    document.getElementById('date_from').max = this.value;
});
</script>
@endpush
