@extends('backend.template.main')

@section('title', 'Material Forecasting')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Material Forecasting</h6>
                            <div class="me-3">
                                @if (auth()->user()->hasRole('pegawai'))
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#forecastModal">
                                    <i class="fas fa-plus me-1"></i> Generate Forecast
                                </button>
                                @endif
                                <a href="{{ route('panel.forecasting.history') }}" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-history me-1"></i> History
                                </a>
                                <a href="{{ route('panel.forecasting.accuracy') }}" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-chart-line me-1"></i> Accuracy
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

                <div class="card-body px-4 pb-2">
                    {{-- Info Card --}}
                    <div class="px-3 mb-4">
                        <div class="alert alert-info mb-0 text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="text-warning mb-1">Metode - Weighted Moving Average (WMA)</h6>
                                    <p class="mb-0">Perkiraan ini menggunakan data penggunaan 3 bulan terakhir dengan bobot (3,2,1) untuk memprediksi pola penggunaan di masa depan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Forecast Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actual Usage</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Predicted</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Error Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($forecasts as $index => $forecast)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $forecast->rawMaterial->name }}</td>
                                    <td>{{ $forecast->rawMaterial->category->name }}</td>
                                    <td>{{ $forecast->date->format('d M Y') }}</td>
                                    <td>
                                        @if(is_null($forecast->actual_usage))
                                            -
                                        @else
                                            {{ number_format($forecast->actual_usage, 1) }} {{ $forecast->rawMaterial->unit }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($forecast->predicted_amount, 1) }} {{ $forecast->rawMaterial->unit }}</td>
                                    <td>
                                        @if(is_null($forecast->error_rate))
                                            -
                                        @else
                                            <span class="badge bg-{{ $forecast->error_rate <= 10 ? 'success' : ($forecast->error_rate <= 20 ? 'warning' : 'danger') }}">
                                                {{ number_format($forecast->error_rate, 1) }}%
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">No forecasts available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Generate Forecast Modal --}}
<div class="modal fade" id="forecastModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate New Forecast</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('panel.forecasting.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="raw_material_id">Material</label>
                        <select name="raw_material_id" id="raw_material_id" class="form-select border px-3" required>
                            <option value="" hidden>---- Select Material ----</option>
                            @foreach($rawMaterials as $material)
                                <option value="{{ $material->id }}" data-stock="{{ $material->stock }}" data-unit="{{ $material->unit }}">
                                    {{ $material->name }} ({{ $material->category->name }})
                                </option>
                            @endforeach
                        </select>
                        <div id="stock-info" class="form-text text-info" style="display: none;">
                            Current stock: <span id="current-stock"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="period">Period (months)</label>
                        <input type="number" class="form-control border px-3" name="period" id="period"
                            value="3" min="3" max="12" required>
                        <div class="form-text text-danger">
                            <i class="fas fa-info-circle me-1 text-danger"></i>
                            Minimum 3 months of historical data required
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.getElementById('raw_material_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const stock = selected.dataset.stock;
    const unit = selected.dataset.unit;

    if (selected.value) {
        document.getElementById('current-stock').textContent = `${stock} ${unit}`;
        document.getElementById('stock-info').style.display = 'block';
    } else {
        document.getElementById('stock-info').style.display = 'none';
    }
});
</script>
@endpush
