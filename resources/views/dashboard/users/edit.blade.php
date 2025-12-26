@extends('layouts.app')
@section('title', 'Edit User - ' . $user->name)

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 mb-1">Edit User</h1>
        <p class="text-muted mb-0">Update user information and permissions</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
        <a href="{{ route('dashboard.users.show', $user->id) }}" class="btn btn-outline-info me-2">
            <i class="fas fa-eye me-1"></i> View Details
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit text-primary me-2"></i>
                    User Information
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('dashboard.users.update', $user->id) }}" id="editUserForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Basic Information Section -->
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                Basic Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-user text-muted me-1"></i>
                                Full Name *
                            </label>
                            <input type="text" 
                                   class="form-control form-control border-0 bg-light" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   style="border-radius: 10px;">
                        </div>
                        
                        <div class="col-md-6">
    <label for="email" class="form-label fw-semibold">
        <i class="fas fa-envelope text-muted me-1"></i>
        Email Address *
    </label>
    <input type="email" 
           class="form-control form-control border-0 bg-light" 
           id="email" 
           name="email" 
           value="{{ old('email', $user->email) }}" 
           required
           autocomplete="username"
           style="border-radius: 10px;">
</div>

                        
                        <!-- Security Section -->
                        <div class="col-12 mt-4">
                            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                Security Settings
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock text-muted me-1"></i>
                                New Password
                            </label>
                            <div class="position-relative">
                               <input type="password" 
       class="form-control form-control border-0 bg-light pe-5" 
       id="password" 
       name="password" 
       placeholder="Leave blank to keep current password"
       autocomplete="new-password"
       style="border-radius: 10px;">
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2" 
                                        onclick="togglePassword('password')" tabindex="-1">
                                    <i class="fas fa-eye text-muted" id="password-eye"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="mt-2"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-lock text-muted me-1"></i>
                                Confirm Password
                            </label>
                            <div class="position-relative">
                                <input type="password" 
       class="form-control form-control border-0 bg-light pe-5" 
       id="password_confirmation" 
       name="password_confirmation" 
       placeholder="Confirm new password"
       autocomplete="new-password"
       style="border-radius: 10px;">
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2" 
                                        onclick="togglePassword('password_confirmation')" tabindex="-1">
                                    <i class="fas fa-eye text-muted" id="password_confirmation-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Permissions Section -->
                        <div class="col-12 mt-4">
                            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                Permissions & Access
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="role" class="form-label fw-semibold">
                                <i class="fas fa-user-tag text-muted me-1"></i>
                                Role *
                            </label>
                            <select class="form-select form-select border-0 bg-light" 
                                    id="role" 
                                    name="role" 
                                    required
                                    style="border-radius: 10px;">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                    <i class="fas fa-crown"></i> Administrator
                                </option>
                                <option value="purchase_team" {{ $user->role == 'purchase_team' ? 'selected' : '' }}>
                                    <i class="fas fa-shopping-cart"></i> Purchase Team
                                </option>
                                <option value="inventory_manager" {{ $user->role == 'inventory_manager' ? 'selected' : '' }}>
                                    <i class="fas fa-boxes"></i> Inventory Manager
                                </option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>
                                    <i class="fas fa-user"></i> User
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fas fa-toggle-on text-muted me-1"></i>
                                Account Status *
                            </label>
                            <select class="form-select form-select border-0 bg-light" 
                                    id="status" 
                                    name="status" 
                                    required
                                    style="border-radius: 10px;">
                                <option value="active" {{ $user->is_active ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle"></i> Active
                                </option>
                                <option value="inactive" {{ !$user->is_active ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle"></i> Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('dashboard.users.show', $user->id) }}" 
                                       class="btn btn-light btn-lg px-4" style="border-radius: 10px;">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                    <button type="reset" 
                                            class="btn btn-outline-secondary btn-lg px-4" 
                                            style="border-radius: 10px;">
                                        <i class="fas fa-undo me-2"></i> Reset Form
                                    </button>
                                </div>
                                <button type="submit" 
                                        class="btn btn-primary btn-lg px-5" 
                                        style="border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                    <i class="fas fa-save me-2"></i> Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Current Profile Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient text-white text-center py-4" 
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                <div class="avatar-lg mb-3 position-relative">
                    <div class="bg-white bg-opacity-20 text-black rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg" 
                         style="width: 90px; height: 90px; font-size: 2.2rem; border: 3px solid black;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="position-absolute bottom-0 end-0 translate-middle">
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} rounded-pill px-3 py-2">
                            <i class="fas fa-{{ $user->is_active ? 'check' : 'times' }} me-1"></i>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <h5 class="mb-1 text-black">{{ $user->name }}</h5>
                <p class="mb-2 text-black opacity-75">{{ $user->email }}</p>
                <span class="badge bg-white bg-opacity-20 text-black px-3 py-2 rounded-pill">
                    <i class="fas fa-{{ $user->role == 'admin' ? 'crown' : ($user->role == 'purchase_team' ? 'shopping-cart' : ($user->role == 'inventory_manager' ? 'boxes' : 'user')) }} me-1"></i>
                    {{ $user->getRoleDisplayName() }}
                </span>
            </div>
        </div>
        
        <!-- Account Information Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Account Information
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-hashtag text-muted"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">User ID</h6>
                        <p class="text-muted mb-0">#{{ $user->id }}</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-plus text-muted"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Account Created</h6>
                        <p class="text-muted mb-0">
                            {{ $user->created_at->format('M d, Y') }}
                            <br><small>{{ $user->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-sign-in-alt text-muted"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Last Login</h6>
                        <p class="text-muted mb-0">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('M d, Y') }}
                                <br><small>{{ $user->last_login_at->diffForHumans() }}</small>
                            @else
                                <span class="text-warning">Never logged in</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-muted"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Last Updated</h6>
                        <p class="text-muted mb-0">
                            {{ $user->updated_at->format('M d, Y') }}
                            <br><small>{{ $user->updated_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($user->id !== auth()->id())
        <!-- Quick Actions Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <button type="button" 
                            class="btn btn-{{ $user->is_active ? 'outline-warning' : 'outline-success' }} btn-lg" 
                            onclick="toggleUserStatus({{ $user->id }}, '{{ $user->is_active ? 'inactive' : 'active' }}')"
                            style="border-radius: 10px;">
                        <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }} me-2"></i> 
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                    </button>
                    <button type="button" 
                            class="btn btn-outline-danger btn-lg" 
                            onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                            style="border-radius: 10px;">
                        <i class="fas fa-trash me-2"></i> Delete User
                    </button>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Role Permissions Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shield-alt text-success me-2"></i>
                    Role Permissions
                </h5>
            </div>
            <div class="card-body p-4">
                <div id="rolePermissions" class="permission-list">
                    <!-- Role permissions will be updated via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permission-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.permission-list li {
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.permission-list li:last-child {
    border-bottom: none;
}

.permission-list .fas.fa-check {
    color: #28a745 !important;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

.card:nth-child(2) {
    animation-delay: 0.1s;
}

.card:nth-child(3) {
    animation-delay: 0.2s;
}
</style>
@endsection


@section('scripts')
<script>
// Role permissions mapping
const rolePermissions = {
    'admin': [
        'Full system access',
        'User management',
        'System configuration',
        'All reports and analytics'
    ],
    'purchase_team': [
        'Purchase order management',
        'Supplier management',
        'Inventory requests',
        'Purchase reports'
    ],
    'inventory_manager': [
        'Inventory management',
        'Stock level monitoring',
        'Asset tracking',
        'Inventory reports'
    ],
    'user': [
        'Basic system access',
        'View assigned data',
        'Create basic requests',
        'Personal profile management'
    ]
};

// Toggle password visibility
 function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(fieldId + '-eye');

        if (field && eye) {
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }
    }
  
// Update role permissions display
function updateRolePermissions(role) {
    const permissionsDiv = document.getElementById('rolePermissions');
    if (role && rolePermissions[role]) {
        const permissions = rolePermissions[role];
        let html = '<ul class="list-unstyled mb-0">';
        permissions.forEach(permission => {
            html += `<li class="d-flex align-items-center">
                <i class="fas fa-check text-success me-3"></i>
                <span>${permission}</span>
            </li>`;
        });
        html += '</ul>';
        permissionsDiv.innerHTML = html;
    } else {
        permissionsDiv.innerHTML = '<p class="text-muted mb-0 text-center">Select a role to see permissions</p>';
    }
}

// Initialize role permissions on page load
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    updateRolePermissions(roleSelect.value);
    
    // Update permissions when role changes
    roleSelect.addEventListener('change', function() {
        updateRolePermissions(this.value);
    });
});

// Form validation
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (password && password !== passwordConfirmation) {
        e.preventDefault();
        showToast('Error', 'Password confirmation does not match', 'error');
        return false;
    }
    
    if (password && password.length < 8) {
        e.preventDefault();
        showToast('Error', 'Password must be at least 8 characters long', 'error');
        return false;
    }
});

// Toggle user status function
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

// Delete user function
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

// Toast notification function
function showToast(title, message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info')} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; border-radius: 10px;';
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strengthDiv = document.getElementById('passwordStrength');
    
    if (!strengthDiv) {
        strengthDiv = document.createElement('div');
        strengthDiv.id = 'passwordStrength';
        strengthDiv.className = 'mt-2';
        this.parentNode.appendChild(strengthDiv);
    }
    
    if (password.length === 0) {
        strengthDiv.innerHTML = '';
        return;
    }
    
    let strength = 0;
    let feedback = [];
    
    if (password.length >= 8) {
        strength++;
    } else {
        feedback.push('At least 8 characters');
    }
    
    if (/[A-Z]/.test(password)) {
        strength++;
    } else {
        feedback.push('One uppercase letter');
    }
    
    if (/[a-z]/.test(password)) {
        strength++;
    } else {
        feedback.push('One lowercase letter');
    }
    
    if (/[0-9]/.test(password)) {
        strength++;
    } else {
        feedback.push('One number');
    }
    
    if (/[^A-Za-z0-9]/.test(password)) {
        strength++;
    } else {
        feedback.push('One special character');
    }
    
    const strengthColors = ['danger', 'danger', 'warning', 'info', 'success'];
    const strengthLabels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    
    let html = `<div class="progress" style="height: 6px; border-radius: 10px;">
        <div class="progress-bar bg-${strengthColors[strength]}" style="width: ${(strength / 5) * 100}%; border-radius: 10px;"></div>
    </div>
    <small class="text-${strengthColors[strength]} fw-medium">Password Strength: ${strengthLabels[strength]}</small>`;
    
    if (feedback.length > 0) {
        html += `<br><small class="text-muted">Missing: ${feedback.join(', ')}</small>`;
    }
    
    strengthDiv.innerHTML = html;
});
</script>
@endsection