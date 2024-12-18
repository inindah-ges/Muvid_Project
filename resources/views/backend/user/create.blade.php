@extends('backend.template.main')

@section('title', 'Create User')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Create New User</h6>
                            <div class="pe-3">
                                <a href="{{ route('panel.user.index') }}" class="btn btn-sm btn-light">
                                    <i class="material-icons text-sm">arrow_back</i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 pb-2">
                    <!-- Error Alerts -->
                    @if($errors->any())
                        <div class="alert alert-danger text-white">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('panel.user.store') }}" method="POST" class="row g-4">
                        @csrf

                        <!-- Basic Information -->
                        <div class="col-md-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static">
                                                <label>Full Name</label>
                                                <input type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       name="name"
                                                       value="{{ old('name') }}"
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group input-group-static">
                                                <label>Email Address</label>
                                                <input type="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       name="email"
                                                       value="{{ old('email') }}"
                                                       required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Security</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static">
                                                <label>Password</label>
                                                <input type="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       name="password"
                                                       required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group input-group-static">
                                                <label>Confirm Password</label>
                                                <input type="password"
                                                       class="form-control"
                                                       name="password_confirmation"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role & Permissions -->
                        <div class="col-md-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Role & Permissions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static">
                                                <label>User Role</label>
                                                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                                    <option value="" hidden>---- Select Role ----</option>
                                                    @foreach($roles as $value => $label)
                                                        <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <p class="text-sm mb-2">Role Permissions:</p>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-items-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Role</th>
                                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Description</th>
                                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Access Level</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Owner</td>
                                                        <td>Full system access with all administrative privileges</td>
                                                        <td><span class="badge bg-danger">Full Access</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Admin</td>
                                                        <td>Business admin with management access</td>
                                                        <td><span class="badge bg-warning">Management</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pegawai</td>
                                                        <td>Staff member with operational access</td>
                                                        <td><span class="badge bg-info">Limited</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pelanggan</td>
                                                        <td>Customer with basic ordering privileges</td>
                                                        <td><span class="badge bg-success">Basic</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-end mt-4">
                                <button type="reset" class="btn btn-secondary me-2">
                                    <i class="material-icons text-sm">refresh</i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-icons text-sm">save</i> Create User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        form.addEventListener('submit', function(e) {
            const password = form.querySelector('input[name="password"]').value;
            const confirmation = form.querySelector('input[name="password_confirmation"]').value;

            if (password !== confirmation) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    });
</script>
@endpush
@endsection
