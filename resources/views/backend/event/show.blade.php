@extends('backend.template.main')

@section('title', 'Event Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Event Details</h6>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $event->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $event->category->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="img-fluid rounded" width="200">
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('panel.event.index') }}" class="btn btn-secondary">Back to List</a>
                        @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('panel.event.edit', $event->uuid) }}" class="btn btn-warning">Edit Event</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
