@extends('backend.template.main')

@section('title', 'Edit Event')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Edit Event</h6>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <form action="{{ route('panel.event.update', $event->uuid) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Event Name</label>
                                    <input type="text" class="form-control border px-3 @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter event name" value="{{ old('name', $event->name) }}">
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
                                    <select class="form-select border px-3 @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                        <option value="" hidden>---- Select Category ----</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                    <label for="image" class="form-label">Event Image</label>
                                    <input type="file" class="form-control border px-3 @error('image') is-invalid @enderror" name="image" id="image" accept="image/*">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <img src ="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" width="200" class="mt-3">
                                </div>
                            </div>
                        </div>

                        <div class="float-end">
                            <a href="{{ route('panel.event.index') }}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
