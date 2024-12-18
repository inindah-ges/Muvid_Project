@extends('backend.template.main')

@section('title', 'Edit Raw Material')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="text-white text-capitalize ps-3">Edit Bahan Baku</h6>
                            </div>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card-body px-0 pb-2">
                        <form action="{{ route('panel.raw-material.update', $rawMaterial->uuid) }}" method="post" class="p-3" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text"
                                            class="form-control border px-3 @error('name') is-invalid @enderror"
                                            value="{{ old('name', $rawMaterial->name) }}" name="name" id="name">

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id"
                                            class="form-select border ps-2 pe-4 @error('category_id') is-invalid @enderror">
                                            <option value="" hidden>---- Choose Category ----</option>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $rawMaterial->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                        </select>

                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input type="number"
                                            class="form-control border px-3 @error('stock') is-invalid @enderror"
                                            value="{{ old('stock', $rawMaterial->stock) }}" name="stock" id="stock">

                                        @error('stock')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <select name="unit" id="unit" class="form-select border ps-2 pe-4 @error('unit') is-invalid @enderror">
                                            <option value="" hidden>---- Choose Unit ----</option>
                                            <option value="pcs" {{ old('unit', $rawMaterial->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                            <option value="gram" {{ old('unit', $rawMaterial->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                                            <option value="kg" {{ old('unit', $rawMaterial->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                            <option value="liter" {{ old('unit', $rawMaterial->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                            <option value="ml" {{ old('unit', $rawMaterial->unit) == 'ml' ? 'selected' : ''}}>Milliliter</option>
                                        </select>

                                        @error('unit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="float-end">
                                <a href="{{ route('panel.raw-material.index') }}" class="btn btn-secondary me-2">Back</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
