@extends('backend.template.main')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Edit User: {{ $user->name }}</h6>
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

                    <form action="{{ route('panel.user.update', $user->uuid) }}" method="POST" class="row g-4">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="col-md-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Basic Information</h6>
                                    <span class="badge bg-primary">ID: #{{ $user->id }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static">
                                                <label>Full Name</label>
                                                <input type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       name="name"
                                                       value="{{ old('name', $user->name) }}"
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
                                                       value="{{ old('email', $user->email) }}"
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

                        <!-- Account Status -->
                        <div class="col-md-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Account Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-2 text-muted">Registration Date</h6>
                                                    <p class="card-text">{{ $user->created_at->format('d M Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-2 text-muted">Last Update</h6>
                                                    <p class="card-text">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-2 text-muted">Status</h6>
                                                    <span class="badge bg-success">Active</span>
                                                </div>
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
                                                    @foreach($roles as $value => $label)
                                                        <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
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

                                    <!-- Activity Summary -->
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-3">Orders Statistics</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>Total Orders:</span>
                                                        <span class="badge bg-primary">{{ $user->orders->count() }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span>Total Sales:</span>
                                                        <span class="badge bg-success">{{ $user->sellings->count() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-3">Last Activity</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>Last Order:</span>
                                                        <span class="badge bg-info">
                                                            {{ $user->orders->sortByDesc('created_at')->first()?->created_at?->format('d M Y') ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span>Last Login:</span>
                                                        <span class="badge bg-warning">
                                                            {{ $user->updated_at->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="col-md-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Security</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <p class="mb-0">To change user password, use the password change button.</p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="button" class="btn btn-warning" onclick="showPasswordModal('{{ $user->uuid }}')">
                                                <i class="material-icons text-sm">key</i> Change Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $user->uuid }}')">
                                    <i class="material-icons text-sm">delete</i> Delete User
                                </button>
                                <div>
                                    <button type="reset" class="btn btn-secondary me-2">
                                        <i class="material-icons text-sm">refresh</i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons text-sm">save</i> Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="passwordForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="input-group input-group-outline mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="input-group input-group-outline">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    // Handle password change modal
    function showPasswordModal(uuid) {
        const modal = new bootstrap.Modal(document.getElementById('passwordModal'));
        const form = document.getElementById('passwordForm');
        form.action = `/panel/user/${uuid}/change-password`;
        modal.show();
    }

    // Handle delete confirmation
    function confirmDelete(uuid) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const form = document.getElementById('deleteForm');
        form.action = `/panel/user/${uuid}`;
        modal.show();
    }

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#passwordForm');

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
