@extends('layouts.app')
@section('title', 'Users Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Users Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-filter"></i> Filter by Role
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index') }}">All Roles</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index', ['role' => 'admin']) }}">Admin</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index', ['role' => 'purchase_team']) }}">Purchase Team</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index', ['role' => 'inventory_manager']) }}">Inventory Manager</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index', ['role' => 'user']) }}">User</a></li>
            </ul>
        </div>
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-eye"></i> Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index') }}">All Status</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index', ['status' => 'active']) }}">Active</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users.index', ['status' => 'inactive']) }}">Inactive</a></li>
            </ul>
        </div>
        <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>
</div>

<!-- Search and Stats -->
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('dashboard.users.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search users by name or email..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search') || request('role') || request('status'))
            <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-danger ms-2">
                <i class="fas fa-times"></i> Clear
            </a>
            @endif
        </form>
    </div>
    <div class="col-md-4">
        <div class="d-flex justify-content-end">
            <div class="text-muted">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>
                            <a href="{{ route('dashboard.users.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-decoration-none text-dark">
                                ID
                                @if(request('sort') === 'id')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @else
                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>Avatar</th>
                        <th>
                            <a href="{{ route('dashboard.users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-decoration-none text-dark">
                                Name
                                @if(request('sort') === 'name')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @else
                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('dashboard.users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-decoration-none text-dark">
                                Email
                                @if(request('sort') === 'email')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @else
                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>
                            <a href="{{ route('dashboard.users.index', array_merge(request()->query(), ['sort' => 'last_login_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-decoration-none text-dark">
                                Last Login
                                @if(request('sort') === 'last_login_at')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @else
                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><small class="text-muted">#{{ $user->id }}</small></td>
                        <td>
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 35px; height: 35px; font-size: 0.8rem;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-info ms-1">You</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'purchase_team' ? 'primary' : ($user->role == 'inventory_manager' ? 'info' : 'secondary')) }}">
                                {{ $user->getRoleDisplayName() }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->last_login_at)
                                <div>{{ $user->last_login_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.users.show', $user->id) }}" 
                                   class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dashboard.users.edit', $user->id) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}" 
                                        onclick="toggleUserStatus({{ $user->id }}, '{{ $user->is_active ? 'inactive' : 'active' }}')"
                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                    <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }}"></i>
                                </button>
                         <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this vendor?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>


                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
                </small>
            </div>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5>No Users Found</h5>
            <p class="text-muted">
                @if(request('search') || request('role') || request('status'))
                    No users match your current filters.
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @else
                    Get started by adding your first user.
                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Quick Stats Cards -->
@if($users->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $totalUsers ?? $users->total() }}</h5>
                <p class="card-text">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $activeUsers ?? 0 }}</h5>
                <p class="card-text">Active Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-danger">{{ $adminUsers ?? 0 }}</h5>
                <p class="card-text">Admin Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">{{ $recentUsers ?? 0 }}</h5>
                <p class="card-text">New This Month</p>
            </div>
        </div>
    </div>
</div>
@endif
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

 // Delete function
    function handleDeleteUser(userId, userName) {
        console.log('handleDeleteUser called with:', {userId, userName});
        
        // Show confirmation with better styling
        const confirmMessage = `Are you sure you want to delete user "${userName || 'Unknown'}"?\n\nThis action cannot be undone.`;
        
        if (!confirm(confirmMessage)) {
            console.log('User cancelled deletion');
            return;
        }
        
        console.log('User confirmed deletion, proceeding...');
        
        // Show loading state
        const deleteBtn = document.querySelector(`button[data-user-id="${userId}"].btn-delete`);
        const originalContent = deleteBtn ? deleteBtn.innerHTML : '';
        
        if (deleteBtn) {
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            console.log('Button disabled and spinner shown');
        }
        
        // Prepare request
        const url = `/dashboard/users/${userId}`;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        console.log('Making DELETE request to:', url);
        console.log('With CSRF token:', token);
        
        // Make the request
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response received:', response.status, response.statusText);
            
            // Handle different response types
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                // If not JSON, treat as text
                return response.text().then(text => {
                    if (response.ok) {
                        return { success: true, message: 'User deleted successfully' };
                    } else {
                        throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                    }
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success || data.success === undefined) {
                console.log('Delete successful!');
                showMessage('Success', data.message || 'User deleted successfully', 'success');
                removeUserRow(userId);
            } else {
                console.error('Delete failed:', data.error);
                showMessage('Error', data.error || 'Failed to delete user', 'error');
                resetDeleteButton(deleteBtn, originalContent);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showMessage('Error', 'Network error: ' + error.message, 'error');
            resetDeleteButton(deleteBtn, originalContent);
        });
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

// Auto-submit search form on Enter key
document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        this.closest('form').submit();
    }
});
</script>
@endsection