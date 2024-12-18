@extends('backend.template.main')

@section('title', 'Ringkasan Laporan Penjualan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ringkasan Laporan Penjualan</h6>
                            <div class="me-3">
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#exportModal">
                                    <i class="fas fa-file-excel me-2"></i>Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body px-4 pb-2">
                    <div class="row mb-4">
                        <!-- Summary Cards -->
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">attach_money</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0">Total Pendapatan (Hari Ini)</p>
                                        @php
                                            $todaySales = $dailySales->firstWhere('date', now()->format('Y-m-d'));
                                        @endphp
                                        <h4 class="mb-0">Rp {{ number_format($todaySales['total_penjualan'] ?? 0, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">shopping_cart</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0">Transaksi (Hari Ini)</p>
                                        <h4 class="mb-0">{{ $todaySales['total_transaksi'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">calendar_month</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0">Total Pendapatan (Bulan Ini)</p>
                                        <h4 class="mb-0">Rp {{ number_format($monthlySales->sum('total_penjualan'), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produk Terjual -->
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">storefront</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0">Produk Terjual (Bulan Ini)</p>
                                        <h4 class="mb-0">{{ $topProducts->sum('total_quantity') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Grafik Penjualan Harian</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="dailySalesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Produk Terlaris</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Terjual</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topProducts as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ number_format($product->total_quantity) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2">Tidak ada data produk terlaris</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Summary Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Ringkasan Penjualan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Periode</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Penjualan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rata-rata Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dailySales->take(7) as $sale)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($sale['date'])->format('d M Y') }}</td>
                                            <td>{{ number_format($sale['total_transaksi']) }}</td>
                                            <td>Rp {{ number_format($sale['total_penjualan'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($sale['rata_rata_transaksi'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
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

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Laporan Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <form action="{{ route('panel.report.sales.export-daily') }}" method="POST" class="list-group-item list-group-item-action">
                        @csrf
                        <input type="hidden" name="date_from" value="{{ now()->startOfMonth() }}">
                        <input type="hidden" name="date_to" value="{{ now() }}">
                        <button type="submit" class="btn btn-link w-100 text-start">
                            <i class="fas fa-file-excel me-2"></i>Export Laporan Harian
                        </button>
                    </form>
                    <form action="{{ route('panel.report.sales.export-monthly') }}" method="POST" class="list-group-item list-group-item-action">
                        @csrf
                        <input type="hidden" name="date_from" value="{{ now()->startOfYear() }}">
                        <input type="hidden" name="date_to" value="{{ now() }}">
                        <button type="submit" class="btn btn-link w-100 text-start">
                            <i class="fas fa-file-excel me-2"></i>Export Laporan Bulanan
                        </button>
                    </form>
                    <form action="{{ route('panel.report.sales.export-yearly') }}" method="POST" class="list-group-item list-group-item-action">
                        @csrf
                        <input type="hidden" name="date_from" value="{{ now()->subYears(5) }}">
                        <input type="hidden" name="date_to" value="{{ now() }}">
                        <button type="submit" class="btn btn-link w-100 text-start">
                            <i class="fas fa-file-excel me-2"></i>Export Laporan Tahunan
                        </button>
                    </form>
                    <form action="{{ route('panel.report.sales.export-products') }}" method="POST" class="list-group-item list-group-item-action">
                        @csrf
                        <input type="hidden" name="date_from" value="{{ now()->startOfMonth() }}">
                        <input type="hidden" name="date_to" value="{{ now() }}">
                        <button type="submit" class="btn btn-link w-100 text-start">
                            <i class="fas fa-file-excel me-2"></i>Export Laporan Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script>
    // Data untuk grafik
    const salesData = @json($dailySales);

    // Format currency
    const formatCurrency = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    };

    // Grafik penjualan harian
const dailySalesChart = new Chart(document.getElementById('dailySalesChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: salesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short'
            });
        }),
        datasets: [{
            label: 'Total Penjualan',
            data: salesData.map(item => item.total_penjualan),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: 'rgb(75, 192, 192)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false,
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    callback: function(value) {
                        return formatCurrency(value);
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return formatCurrency(context.raw);
                    }
                }
            },
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});
</script>
@endpush
