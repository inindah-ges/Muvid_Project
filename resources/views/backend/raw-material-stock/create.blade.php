@extends('backend.template.main')

@section('title', 'Add Stock')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Add Stock Movement</h6>
                            <a href="{{ route('panel.raw-material-stock.index') }}" class="btn btn-sm btn-light me-3">
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

                    <form action="{{ route('panel.raw-material-stock.store') }}" method="POST">
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
                                                {{ old('raw_material_id', request('material')) == $material->id ? 'selected' : '' }}
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
                                    <label for="type" class="ms-0">Movement Type</label>
                                    <select class="form-control border px-3 @error('type') is-invalid @enderror"
                                            name="type" id="type">
                                        <option value="" hidden>---- Select Type ----</option>
                                        <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                        <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label for="quantity" class="ms-0">Quantity</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1"
                                            class="form-control border px-3 @error('quantity') is-invalid @enderror"
                                            name="quantity" id="quantity" value="{{ old('quantity') }}">
                                        <span class="input-group-text px-3" id="unit-display">Unit</span>
                                    </div>
                                    @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label for="notes" class="ms-0">Notes</label>
                                    <textarea class="form-control border px-3 @error('notes') is-invalid @enderror"
                                        name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
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

                        <button type="submit" class="btn btn-primary">Submit</button>
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
