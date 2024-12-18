@extends('frontend.template.main')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Keranjang</h3>
        </div>
        <div class="card-body">
            @if($cartItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                        <thead>
                            <tr>
                                <th>Product/Menu</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th class="text-end">Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end">
                                    <strong>
                                        Rp {{ number_format($cartItems->sum(function($item) {
                                            return $item->product->price * $item->quantity;
                                        }), 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center mt-3 gap-2 d-flex align-items-center justify-content-center">
                    <a href="{{ url('/menu') }}" class="btn btn-secondary">Lanjutkan Belanja</a>
                    <a href="{{ route('checkout') }}" class="btn btn-primary">Lanjutkan ke Pembayaran</a>
                </div>
            @else
                <div class="text-center py-4">
                    <h4>Keranjang Kamu Kosong :(</h4>
                    <a href="{{ url('/menu') }}" class="btn btn-primary mt-3">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
@endif
</script>
@endpush
@endsection
