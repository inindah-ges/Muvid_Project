@extends('backend.template.main')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid py-4">
    {{-- Stock Alert Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-warning shadow-warning border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Low Stock Alert</h6>
                            <div class="me-3">
                                @if (auth()->user()->hasRole('pegawai'))
                                <a href="{{ route('panel.raw-material-stock.create') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-plus me-1"></i> Add Stock
                                </a>
                                @endif
                                <a href="{{ route('panel.raw-material-stock.history') }}" class="btn btn-sm btn-light ms-2">
                                    <i class="fas fa-history me-1"></i> History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Current Stock</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    @if (auth()->user()->hasRole('pegawai'))
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rawMaterials as $material)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $material->name }}</td>
                                    <td>{{ $material->category->name }}</td>
                                    <td>{{ $material->stock }}</td>
                                    <td>{{ $material->unit }}</td>
                                    <td>
                                        @if($material->stock <= 0)
                                            <span class="badge badge-sm bg-gradient-danger">Out of Stock</span>
                                        @elseif($material->stock <= 5)
                                            <span class="badge badge-sm bg-gradient-warning">Critical</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-warning">Low Stock</span>
                                        @endif
                                    </td>
                                    @if (auth()->user()->hasRole('pegawai'))
                                    <td class="pt-4">
                                        <a href="{{ route('panel.raw-material-stock.create', ['material' => $material->id]) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-plus"></i> Stock
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No low stock alerts</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Stock Movement --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Recent Stock Movement</h6>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notes</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Updated By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockHistory as $stock)
                                <tr>
                                    <td>{{ $stock->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $stock->rawMaterial->name }}</td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $stock->type === 'in' ? 'success' : 'danger' }}">
                                            {{ ucfirst($stock->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $stock->quantity }} {{ $stock->rawMaterial->unit }}</td>
                                    <td>{{ $stock->notes ?: '-' }}</td>
                                    <td>{{ $stock->user->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No recent stock movements</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-3 mt-3">
                        {{ $stockHistory->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
