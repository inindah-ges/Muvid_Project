@extends('backend.template.main')

@section('title', 'Testimonial Detail')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Testimonial Detail - {{ $testimonial->selling->invoice }}</h6>
                            <div class="me-3">
                                <a href="{{ route('panel.testimonial.index') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pt-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="150">Name</th>
                                            <td>{{ $testimonial->selling->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $testimonial->selling->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Rating</th>
                                            <td width="60%">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $testimonial->rate)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="fas fa-star"></i>
                                                    @endif
                                                @endfor
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Comment</th>
                                            <td>{{ $testimonial->comment }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
