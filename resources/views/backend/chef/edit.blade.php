@extends('backend.template.main')

@section('title', 'Edit Chef')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-white text-capitalize ps-3">Edit Chef</h6>
                        </div>
                    </div>
                </div>

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <div class="card-body px-0 pb-2">
                    <form action="{{ route('panel.chef.update', $chef->uuid) }}" method="post" class="p-3"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text"
                                        class="form-control border px-3 @error('name') is-invalid @enderror"
                                        value="{{ old('name', $chef->name) }}" name="name" id="name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fb_link" class="form-label">Facebook Link</label>
                                    <input type="url"
                                        class="form-control border px-3 @error('fb_link') is-invalid @enderror"
                                        value="{{ old('fb_link', $chef->fb_link) }}" name="fb_link" id="fb_link"
                                        placeholder="Enter Facebook link">

                                    @error('fb_link')
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
                                    <label for="position" class="form-label">Position</label>
                                    <select name="position" id="position" class="form-select border ps-2 pe-4 @error('position') is-invalid @enderror">
                                        <option value="" hidden>---- Choose Position ----</option>
                                        <option value="Barista" {{ old('position', $chef->position) == 'Barista' ? 'selected' : '' }}>Barista</option>
                                        <option value="Cashier" {{ old('position', $chef->position) == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                                        <option value="Waiter" {{ old('position', $chef->position) == 'Waiter' ? 'selected' : '' }}>Waiter</option>
                                        <option value="Chef" {{ old('position', $chef->position) == 'Chef' ? 'selected' : '' }}>Chef</option>
                                        <option value="Owner" {{ old('position', $chef->position) == 'Owner' ? 'selected' : '' }}>Manager</option>
                                        <option value="Administrator" {{ old('position', $chef->position) == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                                    </select>

                                    @error('position')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="insta_link" class="form-label">Instagram Link</label>
                                    <input type="url"
                                        class="form-control border px-3 @error('insta_link') is-invalid @enderror"
                                        value="{{ old('insta_link', $chef->insta_link) }}" name="insta_link" id="insta_link"
                                        placeholder="Enter Instagram link">

                                    @error('insta_link')
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
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file"
                                        class="form-control border px-3 @error('photo') is-invalid @enderror"
                                        value="{{ old('photo') }}" name="photo" id="photo" placeholder="Enter photo">

                                    @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <img src="{{ asset('storage/' . $chef->photo) }}" class="rounded mt-3" alt="{{ $chef->name }}" width="200">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="linkedin_link" class="form-label">LinkedIn Link</label>
                                    <input type="url"
                                        class="form-control border px-3 @error('linkedin_link') is-invalid @enderror"
                                        value="{{ old('linkedin_link', $chef->linkedin_link) }}" name="linkedin_link" id="linkedin_link"
                                        placeholder="Enter LinkedIn link">

                                    @error('linkedin_link')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="float-end">
                            <a href="{{ route('panel.chef.index') }}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
