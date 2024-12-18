@extends('backend.template.main')

@section('title', 'Tax Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-white text-capitalize ps-3">Tax List</h6>
                            @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('panel.tax.create') }}" class="btn btn-sm btn-primary me-3">
                                <i class="fas fa-plus me-1"></i> Add
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 text-white" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 text-white" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card-body px-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rate</th>
                                    @if (auth()->user()->hasRole('admin'))
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxes as $tax)
                                <tr>
                                    <td>
                                        {{ ($taxes->currentPage() - 1) * $taxes->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $tax->name }}</td>
                                    <td>{{ $tax->rate_percentage }}</td>
                                    @if (auth()->user()->hasRole('admin'))
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('panel.tax.edit', $tax->id) }}"
                                                class="btn btn-info btn-md me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-md me-1"
                                                    onclick="deleteTax(this)"
                                                    data-id="{{ $tax->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 justify-content-center" style="margin-left: 20px; margin-right: 20px;">
                            {{ $taxes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteTax(element) {
        const id = element.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: `/panel/tax/${id}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message,
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endpush
