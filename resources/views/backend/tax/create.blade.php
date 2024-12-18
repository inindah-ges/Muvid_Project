@extends('backend.template.main')

@section('title', 'Add Tax')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-white text-capitalize ps-3">Add Tax</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <form action="{{ route('panel.tax.store') }}" method="post" class="p-4">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Tax Name</label>
                                    <input type="text" class="form-control border px-3 @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rate" class="form-label">Tax Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control border px-3 @error('rate') is-invalid @enderror"
                                        name="rate" id="rate" value="{{ old('rate') }}" required>
                                    @error('rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('panel.tax.index') }}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
