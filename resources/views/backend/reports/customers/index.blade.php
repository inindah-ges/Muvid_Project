@extends('backend.template.main')

@section('title', 'Laporan Pelanggan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Laporan Pelanggan</h6>
                            <div class="me-3">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-file-excel me-2"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="{{ route('panel.report.customers.export-orders') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                            <input type="hidden" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-history me-2"></i>Riwayat Pesanan
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('panel.report.customers.export-behavior') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                            <input type="hidden" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-chart-line me-2"></i>Perilaku Pelanggan
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <!-- Filter Form -->
                    <div class="row align-items-end mb-4">
                        <div class="col-md-3">
                            <div class="input-group input-group-static mb-3">
                                <label for="date_from" class="ms-0">Tanggal Awal</label>
                                <input type="date" class="form-control border px-3" id="date_from" name="date_from"
                                    value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-static mb-3">
                                <label for="date_to" class="ms-0">Tanggal Akhir</label>
                                <input type="date" class="form-control border px-3" id="date_to" name="date_to"
                                    value="{{ request('date_to', now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-info mt-4" onclick="handleFilter()">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">people</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pelanggan</p>
                                        <h4 class="mb-0">{{ $customerOrders->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">shopping_cart</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pesanan</p>
                                        <h4 class="mb-0">{{ $customerOrders->sum('total_pesanan') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">payments</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pendapatan</p>
                                        <h4 class="mb-0">Rp {{ number_format($customerOrders->sum('total_pembelian'), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">trending_up</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Rata-rata Nilai Pesanan</p>
                                        <h4 class="mb-0">{{ $customerOrders->avg('total_pesanan') ? number_format($customerOrders->avg('total_pesanan'), 1) : 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Trend Pesanan Pelanggan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="customerOrdersChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Distribusi Tipe Pesanan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="orderTypeChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Customers Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Pelanggan Teratas</h6>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelanggan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Pesanan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Pembelian</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rata-rata Nilai Pesanan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe Pesanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerBehavior->take(10) as $behavior)
                                        <tr>
                                            <td>{{ $behavior['pelanggan'] }}</td>
                                            <td>{{ $behavior['jumlah_pesanan'] }}</td>
                                            <td>Rp {{ number_format($behavior['total_pembelian'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($behavior['rata_rata_nilai_pesanan'], 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $behavior['tipe_pesanan'] }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data pelanggan</td>
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
// Fungsi untuk handle filter
function handleFilter() {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;

    if (dateFrom && dateTo && dateFrom > dateTo) {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan',
            text: 'Tanggal awal tidak boleh lebih besar dari tanggal akhir!'
        });
        return;
    }

    let url = new URL(window.location.href);
    url.searchParams.set('date_from', dateFrom);
    url.searchParams.set('date_to', dateTo);
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    const customerData = @json($customerBehavior);
    const orderData = @json($customerOrders);

    // Trend pesanan pelanggan chart
    new Chart(document.getElementById('customerOrdersChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: orderData.map(item => item.pelanggan),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: orderData.map(item => item.jumlah_pesanan),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                yAxisID: 'y',
                tension: 0.3,
                fill: true
            }, {
                label: 'Total Pembelian (Rp)',
                data: orderData.map(item => item.total_pembelian),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                yAxisID: 'y1',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Pesanan dan Pembelian per Pelanggan'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Pesanan'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Total Pembelian (Rp)'
                    }
                }
            }
        }
    });

    // Distribusi tipe pesanan chart
    const orderTypes = customerData.reduce((acc, curr) => {
        const type = curr.tipe_pesanan;
        acc[type] = (acc[type] || 0) + curr.jumlah_pesanan;
        return acc;
    }, {});

    new Chart(document.getElementById('orderTypeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(orderTypes),
            datasets: [{
                data: Object.values(orderTypes),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgb(75, 192, 192)',
                    'rgb(255, 99, 132)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label;
                            const value = context.raw;
                            const total = Object.values(orderTypes).reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
