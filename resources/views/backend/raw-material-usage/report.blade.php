@extends('backend.template.main')

@section('title', 'Usage Report')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Usage Report</h6>
                            <div class="me-3">
                                @if($report)
                                    <a href="{{ route('panel.raw-material-usage.export-report', [
                                        'date_from' => request('date_from'),
                                        'date_to' => request('date_to'),
                                        'raw_material_id' => request('raw_material_id')
                                    ]) }}" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-excel me-1"></i> Export
                                    </a>
                                @endif
                                <a href="{{ route('panel.raw-material-usage.index') }}" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('panel.raw-material-usage.report') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
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
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" class="form-control border px-3" name="date_from" id="date_from"
                                        value="{{ $filters['date_from'] ?? '' }}" required max="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" class="form-control border px-3" name="date_to" id="date_to"
                                        value="{{ $filters['date_to'] ?? '' }}" required max="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end mt-4 pt-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    Generate Report
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($report)
                        <div class="border rounded p-3 mb-4 bg-light">
                            <h6>Report Period: {{ $report['period']['from'] }} - {{ $report['period']['to'] }}</h6>
                        </div>

                        {{-- Summary Section --}}
                        <div class="card shadow-none border mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Usage Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Usage</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Average Usage</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usage Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report['summary'] as $summary)
                                            <tr>
                                                <td>{{ $summary['material'] }}</td>
                                                <td>{{ $summary['category'] }}</td>
                                                <td>{{ $summary['total_usage'] }} {{ $summary['unit'] }}</td>
                                                <td>{{ number_format($summary['average_usage'], 1) }} {{ $summary['unit'] }}</td>
                                                <td>{{ $summary['usage_count'] }}x</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Section --}}
                        <div class="card shadow-none border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Usage Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity Used</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Updated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($report['details'] as $index => $usage)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $usage->date->format('d M Y') }}</td>
                                                <td>{{ $usage->rawMaterial->name }}</td>
                                                <td>{{ $usage->rawMaterial->category->name }}</td>
                                                <td>{{ $usage->quantity_used }} {{ $usage->rawMaterial->unit }}</td>
                                                <td>{{ $usage->user->name }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No usage records found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                            <p class="text-secondary">Select date range to generate report</p>
                        </div>
                    @endif
                </div>
            </div>
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
