@extends('backend.template.main')

@section('title', 'Record Usage')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Record Material Usage</h6>
                            <a href="{{ route('panel.raw-material-usage.index') }}" class="btn btn-sm btn-light me-3">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('panel.raw-material-usage.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label for="raw_material_id" class="ms-0">Raw Material</label>
                                    <select class="form-control border px-3 @error('raw_material_id') is-invalid @enderror"
                                            name="raw_material_id" id="raw_material_id">
                                        <option value="" hidden>---- Select Material ----</option>
                                        @foreach($rawMaterials as $material)
                                            <option value="{{ $material->id }}"
                                                {{ old('raw_material_id') == $material->id ? 'selected' : '' }}
                                                data-unit="{{ $material->unit }}"
                                                data-stock="{{ $material->stock }}">
                                                {{ $material->name }} ({{ $material->category->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('raw_material_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label for="quantity_used" class="ms-0">Quantity Used</label>
                                    <div class="input-group">
                                        <input type="number" step="1"
                                            class="form-control border px-3 @error('quantity_used') is-invalid @enderror"
                                            name="quantity_used" id="quantity_used" value="{{ old('quantity_used') }}">
                                        <span class="input-group-text px-3" id="unit-display">Unit</span>
                                    </div>
                                    @error('quantity_used')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label for="date" class="ms-0">Date</label>
                                    <input type="date" class="form-control border px-3 @error('date') is-invalid @enderror"
                                        name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                                        max="{{ date('Y-m-d') }}">
                                    @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info text-white" id="stock-info" style="display: none;">
                                    Current stock: <span id="current-stock">0</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Record Usage</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.getElementById('raw_material_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const unit = selected.dataset.unit;
    const stock = selected.dataset.stock;

    document.getElementById('unit-display').textContent = unit;
    document.getElementById('current-stock').textContent = stock + ' ' + unit;
    document.getElementById('stock-info').style.display = 'block';
});
</script>
@endpush
