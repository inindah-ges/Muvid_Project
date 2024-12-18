@extends('backend.template.main')

@section('title', 'Riwayat Pesanan Pelanggan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Riwayat Pesanan Pelanggan</h6>
                            <div class="me-3">
                                <form action="{{ route('panel.report.customers.export-orders') }}" method="POST">
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
                                        <i class="material-icons opacity-10">shopping_cart</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pesanan</p>
                                        <h4 class="mb-0">{{ count($customerOrders) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">storefront</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Pesanan Makan di Tempat</p>
                                        <h4 class="mb-0">{{ collect($customerOrders)->where('tipe_pesanan', 'Makan di Tempat')->count() }}</h4>
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
                                        <p class="text-sm mb-0 text-capitalize">Pesanan Bawa Pulang</p>
                                        <h4 class="mb-0">{{ $customerOrders->where('tipe_pesanan', 'Bawa Pulang')->sum('total_pesanan') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">payments</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Pendapatan</p>
                                        <h4 class="mb-0">Rp {{ number_format($customerOrders->sum('total_pembelian'), 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order History Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Detail Riwayat Pesanan</h6>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelanggan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Invoice</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe Pesanan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Metode Pembayaran</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Pembayaran</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customerOrders as $order)
                                        <tr>
                                            <td>{{ $order['pelanggan'] }}</td>
                                            <td>{{ $order['invoice'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order['tipe_pesanan'] == 'Makan di Tempat' ? 'success' : 'warning' }}">
                                                    {{ $order['tipe_pesanan'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'confirmed' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $statusLabels = [
                                                        'pending' => 'Menunggu',
                                                        'confirmed' => 'Dikonfirmasi',
                                                        'completed' => 'Selesai',
                                                        'cancelled' => 'Dibatalkan'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$order['status']] }}">
                                                    {{ $statusLabels[$order['status']] }}
                                                </span>
                                            </td>
                                            <td>{{ ucfirst($order['payment_method']) }}</td>
                                            <td>Rp {{ number_format($order['total_pembelian'], 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order['payment_status'] == 'paid' ? 'success' : 'warning' }}">
                                                    {{ $order['payment_status'] == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data pesanan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($customerOrders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-4 px-3">
                                {{ $customerOrders->withQueryString()->links() }}
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
