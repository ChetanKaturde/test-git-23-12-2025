@extends('layouts.app')
@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
        <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-outline-warning me-2">
            <i class="fas fa-edit"></i> Edit User
        </a>
        @if($user->id !== auth()->id())
        <button type="button" class="btn btn-outline-danger" 
                onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
            <i class="fas fa-trash"></i> Delete User
        </button>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>User Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>User ID</h6>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">#{{ $user->id }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Full Name</h6>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">{{ $user->name }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Email Address</h6>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Role</h6>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'purchase_team' ? 'primary' : ($user->role == 'inventory_manager' ? 'info' : 'secondary')) }}">
                            {{ $user->getRoleDisplayName() }}
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Status</h6>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }} ms-2" 
                                onclick="toggleUserStatus({{ $user->id }}, '{{ $user->is_active ? 'inactive' : 'active' }}')">
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Last Login</h6>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('M d, Y g:i A') }}
                                <small class="text-muted">({{ $user->last_login_at->diffForHumans() }})</small>
                            @else
                                Never logged in
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Account Created</h6>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">
                            {{ $user->created_at->format('M d, Y g:i A') }}
                            <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                        </p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6>Last Updated</h6>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">
                            {{ $user->updated_at->format('M d, Y g:i A') }}
                            <small class="text-muted">({{ $user->updated_at->diffForHumans() }})</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Profile Avatar</h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-lg mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </div>
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->getRoleDisplayName() }}</p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    @if($user->id !== auth()->id())
                    <button type="button" class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }}" 
                            onclick="toggleUserStatus({{ $user->id }}, '{{ $user->is_active ? 'inactive' : 'active' }}')">
                        <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }}"></i> 
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button type="button" class="btn btn-danger" 
                            onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleUserStatus(userId, newStatus) {
    if (confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this user?`)) {
        fetch(`/dashboard/users/${userId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success', data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast('Error', data.error || 'Failed to update user status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An unexpected error occurred', 'error');
        });
    }
}

function deleteUser(userId, userName) {
    if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
        fetch(`/dashboard/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success', data.message, 'success');
                setTimeout(() => window.location.href = '{{ route("dashboard.users.index") }}', 1000);
            } else {
                showToast('Error', data.error || 'Failed to delete user', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An unexpected error occurred', 'error');
        });
    }
}

function showToast(title, message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info')} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <strong>${title}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>
@endsection