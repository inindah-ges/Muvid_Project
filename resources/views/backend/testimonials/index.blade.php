@extends('backend.template.main')

@section('title', 'Testimonial')

@section('content')

    {{-- table --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="text-white text-capitalize ps-3">Daftar Testimonial</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pb-2">
                        <div class="table-responsive p-0">
                            <table
                                class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Invoice</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Rating</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Created At</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testimonials as $testimonial)
                                        <tr>
                                            <td>
                                                {{ ($testimonials->currentPage() - 1) * $testimonials->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $testimonial->selling->invoice }}</td>
                                            <td>{{ $testimonial->rate }}</td>
                                            <td>{{ $testimonial->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('panel.testimonial.show', $testimonial->uuid) }}"
                                                        class="btn btn-info btn-md me-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if (auth()->user()->hasRole('admin'))
                                                        <button class="btn btn-danger btn-md me-1"
                                                            onclick="deleteTestimonial(this)"
                                                            data-uuid="{{ $testimonial->uuid }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No Data Available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{-- pagination --}}
                            <div class="mt-3 justify-content-center" style="margin-left: 20px; margin-right: 20px;">
                                {{ $testimonials->links() }}
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
            const deleteTestimonial = (e) => {
                let uuid = e.getAttribute('data-uuid')

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "DELETE",
                            url: `/panel/testimonial/${uuid}`,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 2500,
                                    showConfirmButton: false
                                });

                                window.location.reload();
                            },
                            error: function(data) {
                                Swal.fire({
                                    title: "Failed!",
                                    text: "Your data has not been deleted.",
                                    icon: "error"
                                });

                                console.log(data);
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
