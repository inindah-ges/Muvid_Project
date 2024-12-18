@extends('backend.template.main')

@section('title', 'Raw Material')

@section('content')

{{-- table --}}
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-white text-capitalize ps-3">Daftar Bahan Baku</h6>
                            @if (auth()->user()->hasRole('pegawai'))
                            <a href="{{ route('panel.raw-material.create') }}" class="btn btn-sm btn-primary me-3"><i
                                    class="fas fa-plus me-1"></i> Add</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 text-white" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close text-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 text-white" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close text-white" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif

                <div class="card-body px-4 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-centered table-hover align-items-center justify-content-center text-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Name</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Category</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Stock</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Unit</th>
                                    @if (auth()->user()->hasRole('pegawai'))
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rawMaterials as $material)
                                <tr>
                                    <td>
                                        {{ ($rawMaterials->currentPage() - 1) * $rawMaterials->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $material->name }}</td>
                                    <td>{{ $material->category->name }}</td>
                                    <td>{{ $material->stock }}</td>
                                    <td>{{ $material->unit }}</td>
                                    @if (auth()->user()->hasRole('pegawai'))
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('panel.raw-material.edit', $material->uuid) }}"
                                                class="btn btn-info btn-md me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button class="btn btn-danger btn-md me-1" onclick="deleteRawMaterial(this)"
                                                data-uuid="{{ $material->uuid }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    @endif
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
                            {{ $rawMaterials->links() }}
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
        const deleteRawMaterial = (e) => {
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
                        url: `/panel/raw-material/${uuid}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: data.message,
                                icon: "success",
                                timer: 2500,
                                showConfirmButton: false
                            });

                            window.location.reload();
                        },
                        error: function (data) {
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
