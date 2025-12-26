@extends('layouts.app')
@section('title', 'Users Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Users Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus"></i> Add New User
        </button>
    </div>
</div>

{{-- Success/Error Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Statistics Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <h2>{{ $stats['total_users'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Active Users</h5>
                        <h2>{{ $stats['active_users'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Inactive Users</h5>
                        <h2>{{ $stats['inactive_users'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Search and Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.users.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search users..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="role">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="purchase_team" {{ request('role') == 'purchase_team' ? 'selected' : '' }}>Purchase Team</option>
                        <option value="inventory_manager" {{ request('role') == 'inventory_manager' ? 'selected' : '' }}>Inventory Manager</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Regular User</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="card-header">
        <h5>Users List ({{ $users->total() }} total)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr data-user-id="{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <span class="badge bg-secondary rounded-circle">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'purchase_team' ? 'primary' : ($user->role == 'inventory_manager' ? 'info' : 'secondary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->last_login_at)
                                {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-view" 
                                        data-user-id="{{ $user->id }}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning btn-edit" 
                                        data-user-id="{{ $user->id }}" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }} btn-toggle-status" 
                                        data-user-id="{{ $user->id }}" 
                                        data-status="{{ $user->is_active ? 'inactive' : 'active' }}" 
                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                    <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }}"></i>
                                </button>
                                 @if($user->id !== auth()->id())
<button class="btn btn-danger btn-delete" 
        data-user-id="{{ $user->id }}" 
        data-user-name="{{ $user->name }}"
        type="button">
    <i class="fas fa-trash"></i>
</button>
        @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>No users found matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="form-errors"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="purchase_team">Purchase Team Member</option>
                                    <option value="inventory_manager">Inventory Manager</option>
                                    <option value="user">Regular User</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
console.log('Script loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found! Make sure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your layout.');
        return;
    }
    console.log('CSRF token found:', csrfToken.getAttribute('content'));
    
    // Force remove any onclick attributes from all delete buttons
    const allDeleteButtons = document.querySelectorAll('.btn-delete, [onclick*="deleteUser"]');
    allDeleteButtons.forEach(btn => {
        btn.removeAttribute('onclick');
        console.log('Removed onclick from button:', btn);
    });
    
    // Initialize delete functionality
    initializeDeleteButtons();
    
    function initializeDeleteButtons() {
        // Check if delete buttons exist
        const deleteButtons = document.querySelectorAll('.btn-delete');
        console.log('Found delete buttons:', deleteButtons.length);
        
        if (deleteButtons.length === 0) {
            console.warn('No delete buttons found!');
            return;
        }
        
        // Add event listeners to delete buttons
        deleteButtons.forEach((button, index) => {
            console.log(`Setting up delete button ${index + 1}:`, button);
            console.log('Button data:', {
                userId: button.dataset.userId,
                userName: button.dataset.userName
            });
            
            // Remove any existing onclick attribute - THIS IS CRUCIAL
            button.removeAttribute('onclick');
            
            // Remove any existing event listeners to prevent duplicates
            button.replaceWith(button.cloneNode(true));
            
            // Get the fresh button reference
            const freshButton = document.querySelector(`button[data-user-id="${button.dataset.userId}"].btn-delete`);
            
            // Add click event listener to the fresh button
            freshButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Delete button clicked!');
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                
                console.log('User to delete:', {userId, userName});
                
                if (!userId) {
                    console.error('User ID not found!');
                    showMessage('Error', 'User ID not found', 'error');
                    return;
                }
                
                // Call delete function
                handleDeleteUser(userId, userName);
            });
        });
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
    
    // Reset delete button state
    function resetDeleteButton(button, originalContent = '<i class="fas fa-trash"></i>') {
        if (button) {
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    }
    
    // Remove user row from table
    function removeUserRow(userId) {
        console.log('Removing user row:', userId);
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            row.style.transition = 'opacity 0.3s ease';
            row.style.opacity = '0';
            
            setTimeout(() => {
                row.remove();
                console.log('User row removed');
                
                // Check if table is empty
                const tbody = document.querySelector('table tbody');
                if (tbody && tbody.children.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">No users found.</p>
                            </td>
                        </tr>
                    `;
                }
            }, 300);
        } else {
            console.error('User row not found for ID:', userId);
        }
    }
    
    // Improved message function
    function showMessage(title, message, type) {
        console.log('Showing message:', {title, message, type});
        
        // Remove existing messages
        const existingAlerts = document.querySelectorAll('.temp-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show temp-alert`;
        alert.style.cssText = `
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        `;
        alert.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${iconClass} me-2"></i>
                <div>
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.classList.remove('show');
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 150);
            }
        }, 5000);
    }
    
    console.log('Initialization complete');
});

// TEMPORARY FIX: Global function to handle onclick calls
// This should be removed once HTML is fixed
window.deleteUser = function(userId) {
    console.log('Legacy deleteUser called with ID:', userId);
    const button = document.querySelector(`button[data-user-id="${userId}"].btn-delete`);
    const userName = button ? button.dataset.userName : 'Unknown';
    handleDeleteUser(userId, userName);
};

// Make handleDeleteUser globally accessible for the temporary fix
window.handleDeleteUser = function(userId, userName) {
    console.log('Global handleDeleteUser called with:', {userId, userName});
    
    // Show confirmation
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
        
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json();
        } else {
            return response.text().then(text => {
                if (response.ok) {
                    return { success: true, message: 'User deleted successfully' };
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
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
};
</script>
@endsection