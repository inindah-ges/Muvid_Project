@extends('backend.template.main')

@section('title', 'Laporan Pergerakan Stok')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Laporan Pergerakan Stok</h6>
                            <div class="me-3">
                                <form action="{{ route('panel.report.inventory.export-stock') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                    <input type="hidden" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                    @if(request('raw_material_id'))
                                        <input type="hidden" name="raw_material_id" value="{{ request('raw_material_id') }}">
                                    @endif
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
                            <div class="input-group input-group-static mb-3">
                                <label for="raw_material_id" class="ms-0">Bahan Baku</label>
                                <select class="form-control border px-3" id="raw_material_id" name="raw_material_id">
                                    <option value="">Semua Bahan Baku</option>
                                    @foreach($rawMaterials as $material)
                                        <option value="{{ $material->id }}" {{ request('raw_material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
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
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">add_box</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Stok Masuk</p>
                                        <h4 class="mb-0">{{ $stockMovements->where('type', 'in')->sum('quantity') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">remove_circle</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Stok Keluar</p>
                                        <h4 class="mb-0">{{ $stockMovements->where('type', 'out')->sum('quantity') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">sync_alt</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Transaksi</p>
                                        <h4 class="mb-0">{{ $stockMovements->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">inventory</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Bahan Baku Terlibat</p>
                                        <h4 class="mb-0">{{ $stockMovements->unique('raw_material_id')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Movement Chart -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Grafik Pergerakan Stok</h6>
                                    <p class="text-sm mb-0">
                                        <i class="fa fa-arrow-up text-success"></i>
                                        <span class="font-weight-bold">Stok Masuk</span> &
                                        <i class="fa fa-arrow-down text-danger"></i>
                                        <span class="font-weight-bold">Stok Keluar</span>
                                    </p>
                                </div>
                                <div class="card-body">
                                    <canvas id="stockTrendChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Distribusi per Bahan Baku</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="stockDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Stock Movement Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Detail Pergerakan Stok</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bahan Baku</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Petugas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stockMovements as $movement)
                                        <tr>
                                            <td class="ps-3">{{ $movement->date->format('d M Y') }}</td>
                                            <td class="ps-3">{{ $movement->rawMaterial->name }}</td>
                                            <td class="ps-3">{{ $movement->rawMaterial->category->name }}</td>
                                            <td class="ps-3">
                                                <span class="badge bg-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                                    {{ $movement->type == 'in' ? 'Masuk' : 'Keluar' }}
                                                </span>
                                            </td>
                                            <td class="ps-3">{{ $movement->quantity }} {{ $movement->rawMaterial->unit }}</td>
                                            <td class="ps-3">{{ $movement->user->name }}</td>
                                            <td class="ps-3">{{ $movement->notes ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data pergerakan stok</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($stockMovements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-4">
                                {{ $stockMovements->withQueryString()->links() }}
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
    // Data untuk grafik
    const stockData = @json($stockMovements);

    // Format tanggal untuk labels
    function formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short'
        });
    }

    // Inisialisasi variabel chart global
    let stockTrendChart, stockDistributionChart;

    // Function untuk update chart berdasarkan data
    function updateCharts(data) {
        // Persiapkan data untuk grafik trend
        const groupedByDate = data.reduce((acc, curr) => {
            const date = curr.date.split(' ')[0];
            if (!acc[date]) {
                acc[date] = { in: 0, out: 0 };
            }
            if (curr.type === 'in') {
                acc[date].in += parseFloat(curr.quantity);
            } else {
                acc[date].out += parseFloat(curr.quantity);
            }
            return acc;
        }, {});

        const dates = Object.keys(groupedByDate).sort();

        // Update atau buat trend chart
        if (stockTrendChart) {
            stockTrendChart.data.labels = dates.map(date => formatDate(date));
            stockTrendChart.data.datasets[0].data = dates.map(date => groupedByDate[date].in);
            stockTrendChart.data.datasets[1].data = dates.map(date => groupedByDate[date].out);
            stockTrendChart.update();
        } else {
            stockTrendChart = new Chart(document.getElementById('stockTrendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: dates.map(date => formatDate(date)),
                    datasets: [
                        {
                            label: 'Stok Masuk',
                            data: dates.map(date => groupedByDate[date].in),
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Stok Keluar',
                            data: dates.map(date => groupedByDate[date].out),
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label;
                                    const value = context.raw;
                                    const material = data.find(item =>
                                        formatDate(item.date) === context.label
                                    );
                                    return `${label}: ${value} ${material?.raw_material?.unit || ''}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Persiapkan data untuk grafik distribusi
        const groupedByMaterial = data.reduce((acc, curr) => {
            const materialName = curr.raw_material.name;
            if (!acc[materialName]) {
                acc[materialName] = { in: 0, out: 0 };
            }
            if (curr.type === 'in') {
                acc[materialName].in += parseFloat(curr.quantity);
            } else {
                acc[materialName].out += parseFloat(curr.quantity);
            }
            return acc;
        }, {});

        // Update atau buat distribution chart
        if (stockDistributionChart) {
            stockDistributionChart.data.labels = Object.keys(groupedByMaterial);
            stockDistributionChart.data.datasets[0].data = Object.values(groupedByMaterial)
                .map(val => val.in + val.out);
            stockDistributionChart.update();
        } else {
            stockDistributionChart = new Chart(document.getElementById('stockDistributionChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(groupedByMaterial),
                    datasets: [{
                        data: Object.values(groupedByMaterial).map(val => val.in + val.out),
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgb(75, 192, 192)',
                            'rgb(255, 99, 132)',
                            'rgb(255, 206, 86)',
                            'rgb(153, 102, 255)',
                            'rgb(54, 162, 235)'
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
                                    const materialName = context.label;
                                    const totalValue = context.raw;
                                    const materialData = groupedByMaterial[materialName];
                                    const total = Object.values(groupedByMaterial)
                                        .reduce((sum, val) => sum + (val.in + val.out), 0);
                                    const percentage = ((totalValue / total) * 100).toFixed(1);
                                    return [
                                        `${materialName}: ${percentage}%`,
                                        `Masuk: ${materialData.in}`,
                                        `Keluar: ${materialData.out}`,
                                        `Total: ${totalValue}`
                                    ];
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Function untuk handle filter
    function handleFilter() {
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        const rawMaterialId = document.getElementById('raw_material_id').value;

        // Validasi tanggal
        if (dateFrom && dateTo && dateFrom > dateTo) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Tanggal awal tidak boleh lebih besar dari tanggal akhir!'
            });
            return;
        }

        // Update URL dengan parameter baru
        let url = new URL(window.location.href);
        url.searchParams.set('date_from', dateFrom);
        url.searchParams.set('date_to', dateTo);
        if (rawMaterialId) {
            url.searchParams.set('raw_material_id', rawMaterialId);
        } else {
            url.searchParams.delete('raw_material_id');
        }

        // Redirect ke URL baru
        window.location.href = url.toString();
    }

    // Event handler untuk select bahan baku
    document.getElementById('raw_material_id')?.addEventListener('change', function() {
        const filteredData = this.value
            ? stockData.filter(item => item.raw_material_id.toString() === this.value)
            : stockData;

        if (filteredData.length > 0) {
            updateCharts(filteredData);
        }

        handleFilter();
    });

    // Event handler untuk tombol filter
    document.querySelector('button[type="button"]')?.addEventListener('click', handleFilter);

    // Initialize charts dengan data awal
    updateCharts(stockData);

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
});

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
