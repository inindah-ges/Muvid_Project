@extends('backend.template.main')

@section('title', 'Analisis Perilaku Pelanggan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Analisis Perilaku Pelanggan</h6>
                            <div class="me-3">
                                <form action="{{ route('panel.report.customers.export-behavior') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
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
                                        <p class="text-sm mb-0 text-capitalize">Total Pelanggan Aktif</p>
                                        <h4 class="mb-0">{{ $customerBehavior->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">restaurant</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Makan di Tempat</p>
                                        <h4 class="mb-0">{{ $customerBehavior->where('tipe_pesanan', 'Makan di Tempat')->sum('jumlah_pesanan') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">takeout_dining</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Bawa Pulang</p>
                                        <h4 class="mb-0">{{ $customerBehavior->where('tipe_pesanan', 'Bawa Pulang')->sum('jumlah_pesanan') }}</h4>
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
                                        <p class="text-sm mb-0 text-capitalize">Rata-rata Pembelian</p>
                                        <h4 class="mb-0">Rp {{ number_format($customerBehavior->avg('rata_rata_nilai_pesanan'), 0, ',', '.') }}</h4>
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
                                    <h6>Distribusi Nilai Pesanan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="orderValueChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Preferensi Tipe Pesanan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="orderTypeChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Behavior Analysis Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Analisis Detail Pelanggan</h6>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelanggan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Frekuensi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rata-rata Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Preferensi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tren</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerBehavior as $behavior)
                                        <tr>
                                            <td>{{ $behavior['pelanggan'] }}</td>
                                            <td>
                                                @php
                                                    $avgValue = $behavior['rata_rata_nilai_pesanan'];
                                                    $frequency = $behavior['jumlah_pesanan'];
                                                    if ($avgValue >= 500000 && $frequency >= 5) {
                                                        $category = ['label' => 'Premium', 'color' => 'success'];
                                                    } elseif ($avgValue >= 200000 || $frequency >= 3) {
                                                        $category = ['label' => 'Regular', 'color' => 'info'];
                                                    } else {
                                                        $category = ['label' => 'Casual', 'color' => 'warning'];
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $category['color'] }}">
                                                    {{ $category['label'] }}
                                                </span>
                                            </td>
                                            <td>{{ $behavior['jumlah_pesanan'] }}x/bulan</td>
                                            <td>Rp {{ number_format($behavior['rata_rata_nilai_pesanan'] * $behavior['jumlah_pesanan'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($behavior['rata_rata_nilai_pesanan'], 0, ',', '.') }}</td>
                                            <td>{{ $behavior['tipe_pesanan'] }}</td>
                                            <td>
                                                @php
                                                    $trend = $behavior['rata_rata_nilai_pesanan'] > ($customerBehavior->avg('rata_rata_nilai_pesanan') * 0.8);
                                                @endphp
                                                <i class="fas fa-arrow-{{ $trend ? 'up text-success' : 'down text-danger' }}"></i>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data perilaku pelanggan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($customerBehavior instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-4 px-3">
                                {{ $customerBehavior->withQueryString()->links() }}
                            </div>
                            @endif
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
document.addEventListener('DOMContentLoaded', function() {
    const behaviorData = @json($customerBehavior);

    // Order Value Distribution Chart
    const orderValueChart = new Chart(document.getElementById('orderValueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: behaviorData.length > 0 ? behaviorData.map(item => item.pelanggan) : ['Belum ada data'],
            datasets: [{
                label: 'Rata-rata Nilai Pesanan',
                data: behaviorData.length > 0 ? behaviorData.map(item => item.rata_rata_nilai_pesanan) : [0],
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
                },
                // Tambahkan ini untuk menampilkan pesan saat tidak ada data
                title: {
                    display: behaviorData.length === 0,
                    text: 'Belum ada data perilaku pelanggan',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });

    // Order Type Preference Chart
    const orderTypes = behaviorData.length > 0
        ? behaviorData.reduce((acc, curr) => {
            const type = curr.tipe_pesanan || 'Lainnya';
            acc[type] = (acc[type] || 0) + (curr.jumlah_pesanan || 0);
            return acc;
        }, {})
        : { 'Belum ada data': 1 };

    new Chart(document.getElementById('orderTypeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(orderTypes),
            datasets: [{
                data: Object.values(orderTypes),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgb(75, 192, 192)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 206, 86)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (behaviorData.length === 0) return 'Belum ada data';
                            const value = context.raw;
                            const total = Object.values(orderTypes).reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                // Tambahkan ini untuk menampilkan pesan saat tidak ada data
                title: {
                    display: behaviorData.length === 0,
                    text: 'Belum ada data tipe pesanan',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
});

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

    // Loading state untuk export
    document.querySelector('form')?.addEventListener('submit', function() {
        if (this.getAttribute('action').includes('export')) {
            Swal.fire({
                title: 'Memproses Export...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });

    // Function untuk menghitung persentase perubahan
    function calculateChange(current, previous) {
        if (!previous) return 0;
        return ((current - previous) / previous * 100).toFixed(1);
    }

    // Function untuk format angka
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Function untuk mendapatkan kategori pelanggan
    function getCustomerCategory(avgValue, frequency) {
        if (avgValue >= 500000 && frequency >= 5) {
            return { label: 'Premium', color: 'success' };
        } else if (avgValue >= 200000 || frequency >= 3) {
            return { label: 'Regular', color: 'info' };
        }
        return { label: 'Casual', color: 'warning' };
    }

    // Function untuk menganalisis tren pelanggan
    function analyzeCustomerTrend(customer, avgTotal) {
        const trend = customer.rata_rata_nilai_pesanan > (avgTotal * 0.8);
        return {
            direction: trend ? 'up' : 'down',
            color: trend ? 'success' : 'danger'
        };
    }

    // Customer Lifetime Value Chart
    const clvData = behaviorData.map(customer => {
        return {
            name: customer.pelanggan,
            value: customer.rata_rata_nilai_pesanan * customer.jumlah_pesanan * 12 // Estimasi setahun
        };
    }).sort((a, b) => b.value - a.value);

    new Chart(document.getElementById('customerLifetimeChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: clvData.map(item => item.name),
            datasets: [{
                label: 'Customer Lifetime Value (Estimasi)',
                data: clvData.map(item => item.value),
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgb(153, 102, 255)',
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
                            return 'Rp ' + formatNumber(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + formatNumber(context.raw);
                        }
                    }
                },
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Customer Segmentation Chart
    const segmentationData = behaviorData.reduce((acc, customer) => {
        const category = getCustomerCategory(
            customer.rata_rata_nilai_pesanan,
            customer.jumlah_pesanan
        ).label;
        acc[category] = (acc[category] || 0) + 1;
        return acc;
    }, {});

    new Chart(document.getElementById('customerSegmentationChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: Object.keys(segmentationData),
            datasets: [{
                data: Object.values(segmentationData),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 206, 86)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = Object.values(segmentationData).reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} pelanggan (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
};

// Sweet Alert handler untuk pesan sukses/error
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
    timer: 3000,
    showConfirmButton: false
});
@endif
</script>
@endpush
