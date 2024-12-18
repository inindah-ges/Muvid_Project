@extends('backend.template.main')

@section('title', 'Laporan Penggunaan Bahan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Laporan Penggunaan Bahan</h6>
                            <div class="me-3">
                                <form action="{{ route('panel.report.inventory.export-usage') }}" method="POST" class="d-inline">
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
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">inventory</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Bahan Terpakai</p>
                                        <h4 class="mb-0">{{ $materialUsage->sum('quantity_used') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">category</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Jenis Bahan</p>
                                        <h4 class="mb-0">{{ $materialUsage->unique('raw_material_id')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Petugas</p>
                                        <h4 class="mb-0">{{ $materialUsage->unique('user_id')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">assignment</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Transaksi</p>
                                        <h4 class="mb-0">{{ $materialUsage->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Charts -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Grafik Penggunaan Bahan</h6>
                                    <p class="text-sm mb-0">
                                        <i class="fas fa-circle text-success me-1"></i> Tren penggunaan bahan baku per periode
                                    </p>
                                </div>
                                <div class="card-body">
                                    <canvas id="usageTrendChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Distribusi Penggunaan</h6>
                                    <p class="text-sm mb-0">
                                        <i class="fas fa-circle text-info me-1"></i> Persentase per bahan baku
                                    </p>
                                </div>
                                <div class="card-body">
                                    <canvas id="usageDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Usage Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Penggunaan Bahan Terbanyak</h6>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Bahan Baku</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Kategori</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Total Penggunaan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalUsage = $materialUsage->sum('quantity_used');
                                            $groupedUsage = $materialUsage->groupBy('raw_material_id')
                                                ->map(function($group) {
                                                    return [
                                                        'material' => $group->first()->rawMaterial,
                                                        'total_usage' => $group->sum('quantity_used')
                                                    ];
                                                })
                                                ->sortByDesc('total_usage')
                                                ->take(5);
                                        @endphp

                                        @foreach($groupedUsage as $usage)
                                        <tr>
                                            <td class="ps-3">{{ $usage['material']->name }}</td>
                                            <td class="ps-3">{{ $usage['material']->category->name }}</td>
                                            <td class="ps-3">{{ $usage['total_usage'] }} {{ $usage['material']->unit }}</td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="me-2">{{ number_format(($usage['total_usage'] / $totalUsage) * 100, 1) }}%</span>
                                                    <div>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-gradient-info" role="progressbar"
                                                                 aria-valuenow="{{ ($usage['total_usage'] / $totalUsage) * 100 }}"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: {{ ($usage['total_usage'] / $totalUsage) * 100 }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Usage History Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Riwayat Penggunaan Bahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bahan Baku</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($materialUsage as $usage)
                                        <tr>
                                            <td class="ps-3">{{ $usage->date->format('d M Y') }}</td>
                                            <td class="ps-3">{{ $usage->rawMaterial->name }}</td>
                                            <td class="ps-3">{{ $usage->rawMaterial->category->name }}</td>
                                            <td class="ps-3">{{ $usage->quantity_used }} {{ $usage->rawMaterial->unit }}</td>
                                            <td class="ps-3">{{ $usage->user->name }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data penggunaan bahan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($materialUsage instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-4">
                                {{ $materialUsage->withQueryString()->links() }}
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
    const usageData = @json($materialUsage);

    // Format tanggal untuk labels
    function formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short'
        });
    }

    // Inisialisasi variabel chart global
    let usageTrendChart, usageDistributionChart;

    // Function untuk update chart berdasarkan data
    function updateCharts(data) {
        // Persiapkan data untuk grafik trend
        const groupedByDate = data.reduce((acc, curr) => {
            const date = curr.date.split(' ')[0];
            if (!acc[date]) {
                acc[date] = 0;
            }
            acc[date] += parseFloat(curr.quantity_used);
            return acc;
        }, {});

        const dates = Object.keys(groupedByDate).sort();

        // Update atau buat trend chart
        if (usageTrendChart) {
            usageTrendChart.data.labels = dates.map(date => formatDate(date));
            usageTrendChart.data.datasets[0].data = dates.map(date => groupedByDate[date]);
            usageTrendChart.update();
        } else {
            usageTrendChart = new Chart(document.getElementById('usageTrendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: dates.map(date => formatDate(date)),
                    datasets: [{
                        label: 'Total Penggunaan',
                        data: dates.map(date => groupedByDate[date]),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
                        fill: true
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
                            title: {
                                display: true,
                                text: 'Jumlah Penggunaan'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const material = data.find(item =>
                                        formatDate(item.date) === context.label
                                    );
                                    return `Penggunaan: ${value} ${material?.raw_material?.unit || 'unit'}`;
                                }
                            }
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Persiapkan data untuk grafik distribusi
        const groupedByMaterial = data.reduce((acc, curr) => {
            const materialName = curr.raw_material.name;
            if (!acc[materialName]) {
                acc[materialName] = {
                    total: 0,
                    unit: curr.raw_material.unit
                };
            }
            acc[materialName].total += parseFloat(curr.quantity_used);
            return acc;
        }, {});

        const materialNames = Object.keys(groupedByMaterial);
        const usageValues = materialNames.map(name => groupedByMaterial[name].total);
        const totalUsage = usageValues.reduce((a, b) => a + b, 0);

        // Update atau buat distribution chart
        if (usageDistributionChart) {
            usageDistributionChart.data.labels = materialNames;
            usageDistributionChart.data.datasets[0].data = usageValues;
            usageDistributionChart.update();
        } else {
            usageDistributionChart = new Chart(document.getElementById('usageDistributionChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: materialNames,
                    datasets: [{
                        data: usageValues,
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
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const percentage = ((value / totalUsage) * 100).toFixed(1);
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor[i],
                                            lineWidth: 1,
                                            hidden: false
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const materialName = context.label;
                                    const value = context.raw;
                                    const percentage = ((value / totalUsage) * 100).toFixed(1);
                                    const unit = groupedByMaterial[materialName].unit;
                                    return [
                                        `${materialName}: ${value} ${unit}`,
                                        `Persentase: ${percentage}%`
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
            ? usageData.filter(item => item.raw_material_id.toString() === this.value)
            : usageData;

        if (filteredData.length > 0) {
            updateCharts(filteredData);
        }

        handleFilter();
    });

    // Event handler untuk tombol filter
    document.querySelector('button[type="button"]')?.addEventListener('click', handleFilter);

    // Initialize charts dengan data awal
    if (usageData.length > 0) {
        updateCharts(usageData);
    }

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
