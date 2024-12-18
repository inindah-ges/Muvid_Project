@extends('backend.template.main')

@section('title', 'Forecast Accuracy Analysis')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Forecast Accuracy Analysis</h6>
                            <a href="{{ route('panel.forecasting.index') }}" class="btn btn-sm btn-light me-3">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Filter Form --}}
                    <form action="{{ route('panel.forecasting.accuracy') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="raw_material_id">Material</label>
                                    <select name="raw_material_id" id="raw_material_id" class="form-select border px-3" required>
                                        <option value="" hidden>---- Select Material ----</option>
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
                                        value="{{ $filters['date_from'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" class="form-control border px-3" name="date_to" id="date_to"
                                        value="{{ $filters['date_to'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end mt-3 pt-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    Analyze
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($analysis)
                        {{-- Summary Cards --}}
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="text-sm mb-0">Total Forecasts</h6>
                                        <h4 class="font-weight-bold mb-0">{{ $analysis['total_forecasts'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="text-sm mb-0">Average Error Rate</h6>
                                        <h4 class="font-weight-bold mb-0">{{ number_format($analysis['average_error'], 1) }}%</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="text-sm mb-0">Min Error Rate</h6>
                                        <h4 class="font-weight-bold mb-0">{{ number_format($analysis['min_error'], 1) }}%</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="text-sm mb-0">Max Error Rate</h6>
                                        <h4 class="font-weight-bold mb-0">{{ number_format($analysis['max_error'], 1) }}%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Trend Chart --}}
                        <div class="card shadow-none border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Accuracy Trend</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="accuracyChart" height="300"></canvas>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-secondary mb-3"></i>
                            <p class="text-secondary">Select a material to view accuracy analysis</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');

        if (dateFrom) {
            dateFrom.addEventListener('change', function() {
                dateTo.min = this.value;
            });
        }

        if (dateTo) {
            dateTo.addEventListener('change', function() {
                dateFrom.max = this.value;
            });
        }

        @if($analysis)
            // console.log('Analysis Data:', @json($analysis));
            const accuracyCanvas = document.getElementById('accuracyChart');

            if (accuracyCanvas) {
                const chart = new Chart(accuracyCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($analysis['accuracy_trend']->pluck('date')),
                        datasets: [{
                            label: 'Error Rate (%)',
                            data: @json($analysis['accuracy_trend']->pluck('error_rate')),
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Error Rate (%)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        }
                    }
                });
            }
        @endif
    });
</script>
@endpush
