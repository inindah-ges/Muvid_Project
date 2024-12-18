@extends('backend.template.main')

@section('title', 'Chef Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Chef Details</h6>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $chef->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Position</th>
                                        <td>{{ $chef->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Instagram</th>
                                        <td>
                                            <a href="{{ $chef->insta_link }}" target="_blank">{{ $chef->insta_link }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Facebook</th>
                                        <td>
                                            <a href="{{ $chef->fb_link }}" target="_blank">{{ $chef->fb_link }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>LinkedIn</th>
                                        <td>
                                            <a href="{{ $chef->linkedin_link }}" target="_blank">{{ $chef->linkedin_link }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('storage/' . $chef->photo) }}" alt="{{ $chef->name }}" class="img-fluid rounded-circle" width="200">
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('panel.chef.index') }}" class="btn btn-secondary">Back to List</a>
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('panel.chef.edit', $chef->uuid) }}" class="btn btn-warning">Edit Chef</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
