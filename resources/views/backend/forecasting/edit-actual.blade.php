@extends('backend.template.main')

@section('title', 'Update Actual Usage')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Update Actual Usage</h6>
                            <a href="{{ route('panel.forecasting.history') }}" class="btn btn-sm btn-light me-3">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <form action="{{ route('panel.forecasting.update-actual', $forecast->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="forecast_id" value="{{ $forecast->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label class="ms-0">Material</label>
                                    <input type="text" class="form-control border px-3"
                                        value="{{ $forecast->rawMaterial->name }} ({{ $forecast->rawMaterial->category->name }})"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label class="ms-0">Forecast Date</label>
                                    <input type="text" class="form-control border px-3"
                                        value="{{ $forecast->date->format('F Y') }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label class="ms-0">Predicted Amount</label>
                                    <input type="text" class="form-control border px-3"
                                        value="{{ number_format($forecast->predicted_amount, 2) }} {{ $forecast->rawMaterial->unit }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label for="actual_usage" class="ms-0">Actual Usage</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01"
                                            class="form-control border px-3 @error('actual_usage') is-invalid @enderror"
                                            name="actual_usage"
                                            value="{{ old('actual_usage', $forecast->actual_usage) }}"
                                            required>
                                        <span class="input-group-text me-3 p-2">{{ $forecast->rawMaterial->unit }}</span>
                                    </div>
                                    @error('actual_usage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Actual Usage</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
