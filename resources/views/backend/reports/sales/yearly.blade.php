@extends('backend.template.main')

@section('title', 'Laporan Penjualan Tahunan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Laporan Penjualan Tahunan</h6>
                            <div class="me-3">
                                <form action="{{ route('panel.report.sales.export-yearly') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="date_from" value="{{ request('date_from', now()->subYears(5)->format('Y-m-d')) }}">
                                    <input type="hidden" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                    <button type="submit" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-excel me-2"></i>Export Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <!-- Filter Form -->
                    <form action="" method="GET" class="row align-items-end mb-4">
                        <div class="col-md-3">
                            <div class="input-group input-group-static mb-3">
                                <label for="year_from" class="ms-0">Dari Tahun</label>
                                <select class="form-control border px-3" id="year_from" name="year_from">
                                    @for($i = now()->year; $i >= now()->subYears(10)->year; $i--)
                                        <option value="{{ $i }}" {{ request('year_from', now()->subYears(5)->year) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-static mb-3">
                                <label for="year_to" class="ms-0">Sampai Tahun</label>
                                <select class="form-control border px-3" id="year_to" name="year_to">
                                    @for($i = now()->year; $i >= now()->subYears(10)->year; $i--)
                                        <option value="{{ $i }}" {{ request('year_to', now()->year) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info mt-4">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">date_range</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Jumlah Tahun</p>
                                        <h4 class="mb-0">{{ $sales->count() }} tahun</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">receipt_long</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Transaksi</p>
                                        <h4 class="mb-0">{{ number_format($sales->sum('total_transaksi')) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">payments</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pendapatan</p>
                                        <h4 class="mb-0">Rp {{ number_format($sales->sum('total_penjualan'), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">analytics</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Rata-rata Tahunan</p>
                                        <h4 class="mb-0">Rp {{ number_format($sales->avg('total_penjualan'), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yearly Charts -->
                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Trend Penjualan Tahunan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="yearlySalesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Distribusi Pendapatan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="yearlyDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yearly Analysis Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Analisis Penjualan Tahunan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Penjualan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rata-rata per Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pertumbuhan YoY</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kontribusi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $previousSales = 0;
                                            $totalSales = $sales->sum('total_penjualan');
                                        @endphp
                                        @forelse($sales as $sale)
                                        <tr>
                                            <td>{{ $sale['tahun'] }}</td>
                                            <td>{{ number_format($sale['total_transaksi']) }}</td>
                                            <td>Rp {{ number_format($sale['total_penjualan'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($sale['total_penjualan'] / $sale['total_transaksi'], 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $growth = $previousSales != 0
                                                        ? (($sale['total_penjualan'] - $previousSales) / $previousSales) * 100
                                                        : 0;
                                                    $previousSales = $sale['total_penjualan'];
                                                @endphp
                                                <span class="badge bg-{{ $growth > 0 ? 'success' : ($growth < 0 ? 'danger' : 'secondary') }}">
                                                    {{ $growth > 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $contribution = $totalSales > 0 ? (($sale['total_penjualan'] / $totalSales) * 100) : 0;
                                                @endphp
                                                <div class="d-flex justify-content-center">
                                                    <div class="progress-bar bg-gradient-info p-3"
                                                        role="progressbar"
                                                        style="width: {{ $contribution }}%"
                                                        aria-valuenow="{{ $contribution }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ number_format($contribution, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">Tidak ada data penjualan</td>
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
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik
    const salesData = @json($sales);

    // Grafik trend penjualan tahunan
    new Chart(document.getElementById('yearlySalesChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: salesData.map(item => item.tahun),
            datasets: [{
                label: 'Total Penjualan',
                data: salesData.map(item => item.total_penjualan),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }, {
                label: 'Total Transaksi',
                data: salesData.map(item => item.total_transaksi * 1000), // Menyesuaikan skala
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1,
                fill: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID').format(value/1000);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Penjualan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            } else {
                                return 'Transaksi: ' + new Intl.NumberFormat('id-ID').format(context.raw/1000);
                            }
                        }
                    }
                }
            }
        }
    });

    // Grafik distribusi pendapatan
    new Chart(document.getElementById('yearlyDistributionChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: salesData.map(item => `Tahun ${item.tahun}`),
            datasets: [{
                data: salesData.map(item => parseInt(item.total_penjualan)),  // Pastikan data numerik
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Tambahan interaktivitas untuk filter tahun
    document.getElementById('year_from').addEventListener('change', function() {
        const yearFrom = parseInt(this.value);
        const yearTo = parseInt(document.getElementById('year_to').value);

        if (yearFrom > yearTo) {
            document.getElementById('year_to').value = yearFrom;
        }
    });

    document.getElementById('year_to').addEventListener('change', function() {
        const yearTo = parseInt(this.value);
        const yearFrom = parseInt(document.getElementById('year_from').value);

        if (yearTo < yearFrom) {
            document.getElementById('year_from').value = yearTo;
        }
    });
</script>
@endpush

<!-- Tambahan Modal Analisis -->
<div class="modal fade" id="yearAnalysisModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Analisis Detail Tahun <span id="selectedYear"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Quarterly Analysis -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6>Analisis Kuartal</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="quarterlyAnalysisChart" height="200"></canvas>
                    </div>
                </div>

                <!-- Monthly Distribution -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6>Distribusi Bulanan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="monthlyDistributionTable">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Total Transaksi</th>
                                        <th>Total Penjualan</th>
                                        <th>Kontribusi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Bulan Terbaik</h6>
                            </div>
                            <div class="card-body" id="bestMonthMetrics">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Bulan Terlemah</h6>
                            </div>
                            <div class="card-body" id="worstMonthMetrics">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<!-- Tambahan JavaScript untuk Modal Analisis -->
<script>
    // Function untuk membuka modal analisis tahun
    function openYearAnalysis(year) {
        const modal = new bootstrap.Modal(document.getElementById('yearAnalysisModal'));
        document.getElementById('selectedYear').textContent = year;

        // Simulasi data untuk demo (dalam implementasi sebenarnya, data akan diambil dari server)
        const quarterlyData = {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'Penjualan per Kuartal',
                data: [
                    Math.random() * 1000000000,
                    Math.random() * 1000000000,
                    Math.random() * 1000000000,
                    Math.random() * 1000000000
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Render grafik kuartal
        const quarterlyCtx = document.getElementById('quarterlyAnalysisChart').getContext('2d');
        new Chart(quarterlyCtx, {
            type: 'bar',
            data: quarterlyData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                }
            }
        });

        // Populate monthly distribution table
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const tableBody = document.getElementById('monthlyDistributionTable').getElementsByTagName('tbody')[0];
        tableBody.innerHTML = '';

        monthNames.forEach(month => {
            const row = tableBody.insertRow();
            const sales = Math.random() * 100000000;
            const transactions = Math.floor(Math.random() * 1000);

            row.innerHTML = `
                <td>${month}</td>
                <td>${new Intl.NumberFormat('id-ID').format(transactions)}</td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(sales)}</td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-gradient-success"
                            role="progressbar"
                            style="width: ${Math.random() * 100}%"
                            aria-valuenow="${Math.random() * 100}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </td>
            `;
        });

        // Populate best/worst month metrics
        document.getElementById('bestMonthMetrics').innerHTML = `
            <div class="text-center">
                <h3 class="mb-0 text-success">Agustus ${year}</h3>
                <p class="text-sm mb-0">Total Penjualan</p>
                <h4 class="mb-0">Rp ${new Intl.NumberFormat('id-ID').format(Math.random() * 100000000)}</h4>
                <p class="text-sm mb-0">${new Intl.NumberFormat('id-ID').format(Math.floor(Math.random() * 1000))} Transaksi</p>
            </div>
        `;

        document.getElementById('worstMonthMetrics').innerHTML = `
            <div class="text-center">
                <h3 class="mb-0 text-danger">Februari ${year}</h3>
                <p class="text-sm mb-0">Total Penjualan</p>
                <h4 class="mb-0">Rp ${new Intl.NumberFormat('id-ID').format(Math.random() * 50000000)}</h4>
                <p class="text-sm mb-0">${new Intl.NumberFormat('id-ID').format(Math.floor(Math.random() * 500))} Transaksi</p>
            </div>
        `;

        modal.show();
    }

    // Tambahkan event listener untuk klik baris tabel
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            const year = this.cells[0].textContent;
            openYearAnalysis(year);
        });
    });
</script>
@endpush
