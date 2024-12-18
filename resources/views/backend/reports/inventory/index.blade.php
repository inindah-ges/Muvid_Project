@extends('backend.template.main')

@section('title', 'Laporan Inventaris')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Laporan Inventaris</h6>
                            <div class="me-3">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-file-excel me-2"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="{{ route('panel.report.inventory.export-stock') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                            <input type="hidden" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-box me-2"></i>Pergerakan Stok
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('panel.report.inventory.export-usage') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="date_from" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                            <input type="hidden" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-chart-line me-2"></i>Penggunaan Bahan
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

                    <!-- Menyesuaikan Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">inventory</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Bahan Baku</p>
                                        <h4 class="mb-0">{{ $rawMaterials->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">assignment</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pergerakan</p>
                                        <h4 class="mb-0">{{ $stockMovements->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">report_problem</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Stok Menipis</p>
                                        <h4 class="mb-0">{{ $lowStockItems->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">local_shipping</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Penggunaan</p>
                                        <h4 class="mb-0">{{ $materialUsage->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menyesuaikan bagian Low Stock Alert -->
                    @if($lowStockItems->isNotEmpty())
                        <div class="alert alert-warning" role="alert">
                            <div class="d-flex">
                                <i class="material-icons me-2 mt-2">warning</i>
                                <div>
                                    <h4 class="alert-heading mb-2">Peringatan Stok Menipis</h4>
                                    <p class="mb-0">Beberapa bahan baku memiliki stok di bawah batas minimum:</p>
                                    <ul class="mb-0">
                                        @foreach($lowStockItems as $item)
                                        <li>{{ $item['name'] }} (Sisa: {{ $item['stock'] }} {{ $item['unit'] }}) - {{ $item['category'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Stock Movement Chart -->
                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Grafik Pergerakan Stok</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="stockMovementChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Distribusi Penggunaan</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="usageDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Stock Movements -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Pergerakan Stok Terbaru</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bahan Baku</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Petugas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stockMovements->take(10) as $movement)
                                        <tr>
                                            <td class="ps-3">{{ $movement->date->format('d M Y') }}</td>
                                            <td class="ps-3">{{ $movement->rawMaterial->name }}</td>
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
                                            <td colspan="6" class="text-center">Tidak ada data pergerakan stok</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Material Usage -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Penggunaan Bahan Baku Terbaru</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bahan Baku</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Penggunaan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($materialUsage->take(10) as $usage)
                                        <tr>
                                            <td class="ps-3">{{ $usage->date->format('d M Y') }}</td>
                                            <td class="ps-3">{{ $usage->rawMaterial->name }}</td>
                                            <td class="ps-3">{{ $usage->quantity_used }} {{ $usage->rawMaterial->unit }}</td>
                                            <td class="ps-3">{{ $usage->user->name }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data penggunaan bahan</td>
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
    const stockMovementData = @json($stockMovements);
    const usageData = @json($materialUsage);

    // Format tanggal untuk labels
    function formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    // Deklarasi variabel chart global
    let stockMovementChart, usageDistributionChart;

    // Inisialisasi grafik ketika DOM sudah siap
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik pergerakan stok
        stockMovementChart = new Chart(document.getElementById('stockMovementChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: [...new Set(stockMovementData.map(item => formatDate(item.date)))], // Menggunakan Set untuk menghapus duplikat
                datasets: [{
                    label: 'Stok Masuk',
                    data: stockMovementData
                        .filter(item => item.type === 'in')
                        .reduce((acc, item) => {
                            const date = formatDate(item.date);
                            const existingIndex = acc.findIndex(d => d.x === date);
                            if (existingIndex > -1) {
                                acc[existingIndex].y += parseFloat(item.quantity);
                            } else {
                                acc.push({
                                    x: date,
                                    y: parseFloat(item.quantity)
                                });
                            }
                            return acc;
                        }, []),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }, {
                    label: 'Stok Keluar',
                    data: stockMovementData
                        .filter(item => item.type === 'out')
                        .reduce((acc, item) => {
                            const date = formatDate(item.date);
                            const existingIndex = acc.findIndex(d => d.x === date);
                            if (existingIndex > -1) {
                                acc[existingIndex].y += parseFloat(item.quantity);
                            } else {
                                acc.push({
                                    x: date,
                                    y: parseFloat(item.quantity)
                                });
                            }
                            return acc;
                        }, []),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1,
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
                            text: 'Jumlah'
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
                                const label = context.dataset.label || '';
                                const value = context.parsed.y || 0;
                                const material = stockMovementData.find(item =>
                                    formatDate(item.date) === context.label &&
                                    item.type === (context.datasetIndex === 0 ? 'in' : 'out')
                                );
                                return `${label}: ${value} ${material?.rawMaterial?.unit || ''}`;
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Grafik distribusi penggunaan
        const uniqueUsages = usageData.reduce((acc, curr) => {
            const existingIndex = acc.findIndex(item => item.material_id === curr.raw_material.id);
            if (existingIndex > -1) {
                acc[existingIndex].quantity_used += parseFloat(curr.quantity_used);
            } else {
                acc.push({
                    material_id: curr.raw_material.id,
                    name: curr.raw_material.name,
                    quantity_used: parseFloat(curr.quantity_used),
                    unit: curr.raw_material.unit
                });
            }
            return acc;
        }, []);

        usageDistributionChart = new Chart(document.getElementById('usageDistributionChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: uniqueUsages.map(item => item.name),
                datasets: [{
                    data: uniqueUsages.map(item => item.quantity_used),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor[i],
                                            lineWidth: 1,
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                const material = uniqueUsages[context.dataIndex];
                                return `${context.label}: ${value} ${material.unit} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });

    // Fungsi untuk memperbarui grafik berdasarkan filter
    function updateCharts(materialName) {
        const filteredStockMovements = materialName === 'Semua Bahan Baku'
            ? stockMovementData
            : stockMovementData.filter(item => item.raw_material.name === materialName);

        const filteredUsage = materialName === 'Semua Bahan Baku'
            ? usageData
            : usageData.filter(item => item.raw_material.name === materialName);

        // Update chart stok
        if (stockMovementChart) {
            stockMovementChart.data.labels = filteredStockMovements.map(item => formatDate(item.date));
            stockMovementChart.data.datasets[0].data = filteredStockMovements
                .filter(item => item.type === 'in')
                .map(item => parseFloat(item.quantity));
            stockMovementChart.data.datasets[1].data = filteredStockMovements
                .filter(item => item.type === 'out')
                .map(item => parseFloat(item.quantity));
            stockMovementChart.update();
        }

        // Update chart penggunaan
        if (usageDistributionChart) {
            const uniqueFilteredUsages = filteredUsage.reduce((acc, curr) => {
                const existingIndex = acc.findIndex(item => item.material_id === curr.raw_material.id);
                if (existingIndex > -1) {
                    acc[existingIndex].quantity_used += parseFloat(curr.quantity_used);
                } else {
                    acc.push({
                        material_id: curr.raw_material.id,
                        name: curr.raw_material.name,
                        quantity_used: parseFloat(curr.quantity_used),
                        unit: curr.raw_material.unit
                    });
                }
                return acc;
            }, []);

            usageDistributionChart.data.labels = uniqueFilteredUsages.map(item => item.name);
            usageDistributionChart.data.datasets[0].data = uniqueFilteredUsages.map(item => item.quantity_used);
            usageDistributionChart.update();
        }
    }

    // Event handler untuk filter
    document.getElementById('raw_material_id')?.addEventListener('change', function() {
        const selectedMaterial = this.options[this.selectedIndex].text;
        updateCharts(selectedMaterial);
    });
</script>
@endpush
