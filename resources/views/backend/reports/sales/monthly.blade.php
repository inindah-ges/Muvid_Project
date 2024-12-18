@extends('backend.template.main')

@section('title', 'Laporan Penjualan Bulanan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Laporan Penjualan Bulanan</h6>
                            <div class="me-3">
                                <form action="{{ route('panel.report.sales.export-monthly') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfYear()->format('Y-m-d')) }}">
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
                                <label for="date_from" class="ms-0">Dari Bulan</label>
                                <input type="month" class="form-control border px-3" id="date_from" name="date_from"
                                    value="{{ request('date_from', now()->startOfYear()->format('Y-m')) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-static mb-3">
                                <label for="date_to" class="ms-0">Sampai Bulan</label>
                                <input type="month" class="form-control border px-3" id="date_to" name="date_to"
                                    value="{{ request('date_to', now()->format('Y-m')) }}">
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
                                        <i class="material-icons opacity-10">calendar_month</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Jumlah Bulan</p>
                                        <h4 class="mb-0">{{ $sales->count() }} bulan</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">shopping_cart</i>
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
                                        <i class="material-icons opacity-10">trending_up</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Rata-rata per Bulan</p>
                                        <h4 class="mb-0">Rp {{ number_format($sales->avg('total_penjualan'), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Sales Charts -->
                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Grafik Penjualan Bulanan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlySalesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Perbandingan Transaksi</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="transactionPieChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Sales Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Detail Penjualan Bulanan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Penjualan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rata-rata per Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pertumbuhan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $previousSales = 0;
                                        @endphp
                                        @forelse($sales as $sale)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::createFromDate($sale['tahun'], $sale['bulan'], 1)->format('M Y') }}</td>
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
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5">Tidak ada data penjualan</td>
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

<script>
    // Data untuk grafik
    const salesData = @json($sales);

    // Grafik penjualan bulanan
    new Chart(document.getElementById('monthlySalesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: salesData.map(item => `${item.bulan}/${item.tahun}`),
            datasets: [{
                label: 'Total Penjualan',
                data: salesData.map(item => item.total_penjualan),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
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

    // Grafik pie perbandingan transaksi
    new Chart(document.getElementById('transactionPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: salesData.map(item => `${item.bulan}/${item.tahun}`),
            datasets: [{
                data: salesData.map(item => item.total_transaksi),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
</script>
@endpush
