@extends('layouts.app')
@section('title', 'Create New User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New User</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.users.store') }}" id="createUserForm">
                    @csrf
                    
                    <div class="row mb-3">
                        <label for="name" class="col-md-3 col-form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Enter full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="email" class="col-md-3 col-form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="Enter email address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="role" class="col-md-3 col-form-label">Role <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Administrator
                                </option>
                                <option value="purchase_team" {{ old('role') == 'purchase_team' ? 'selected' : '' }}>
                                    Purchase Team
                                </option>
                                <option value="inventory_manager" {{ old('role') == 'inventory_manager' ? 'selected' : '' }}>
                                    Inventory Manager
                                </option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Role determines user permissions and access levels.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="status" class="col-md-3 col-form-label">Status</label>
                        <div class="col-md-9">
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    Inactive users cannot log in to the system.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Password Setup <span class="text-danger">*</span></h6>
                    
                    <div class="row mb-3">
                        <label for="password" class="col-md-3 col-form-label">Password <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required 
                                   placeholder="Enter password (minimum 8 characters)">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    Password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <label for="password_confirmation" class="col-md-3 col-form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required 
                                   placeholder="Re-enter password">
                            <div class="form-text">
                                <small class="text-muted">
                                    Re-enter the password to confirm.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                 <!-- Module Permissions Section -->
<div id="modulePermissionsSection" style="display: none;">
    <h6 class="mb-3">Module Permissions</h6>
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Select Module Access</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" id="selectAllModules">Select All</button>
                        <button type="button" class="btn btn-outline-secondary" id="clearAllModules">Clear All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($modules as $module)
                        <div class="col-md-6 mb-3">
                            <div class="card module-card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="{{ $module->icon }} text-primary me-2"></i>
                                        <h6 class="mb-0">{{ $module->name }}</h6>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input module-view" type="checkbox" 
                                               id="module_{{ $module->id }}_view" 
                                               name="permissions[{{ $module->id }}][view]" 
                                               value="1"
                                               {{ old("permissions.{$module->id}.view") ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="module_{{ $module->id }}_view">
                                            Can View
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input module-edit" type="checkbox" 
                                               id="module_{{ $module->id }}_edit" 
                                               name="permissions[{{ $module->id }}][edit]" 
                                               value="1"
                                               {{ old("permissions.{$module->id}.edit") ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="module_{{ $module->id }}_edit">
                                            Can Edit
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input module-create" type="checkbox" 
                                               id="module_{{ $module->id }}_create" 
                                               name="permissions[{{ $module->id }}][create]" 
                                               value="1"
                                               {{ old("permissions.{$module->id}.create") ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="module_{{ $module->id }}_create">
                                            Can Create
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input module-delete" type="checkbox" 
                                               id="module_{{ $module->id }}_delete" 
                                               name="permissions[{{ $module->id }}][delete]" 
                                               value="1"
                                               {{ old("permissions.{$module->id}.delete") ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="module_{{ $module->id }}_delete">
                                            Can Delete
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>

                    
                    <div class="row mb-3">
                        <div class="col-md-9 offset-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="send_welcome_email" 
                                       name="send_welcome_email" value="1" {{ old('send_welcome_email', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_welcome_email">
                                    Send welcome email with login credentials
                                </label>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    The user will receive an email with their login details and instructions.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-9 offset-md-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-plus"></i> Create User
                            </button>
                            <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Preview</h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-lg mb-3">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2rem;" id="userAvatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <h5 id="previewName">New User</h5>
                <p class="text-muted" id="previewEmail">user@example.com</p>
                <span class="badge bg-secondary mb-2" id="previewRole">Select Role</span>
                <br>
                <span class="badge bg-success" id="previewStatus">Active</span>
                <div class="mt-3">
                    <small class="text-muted">Module Access: <span id="moduleCount">0</span></small>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Role Permissions</h5>
            </div>
            <div class="card-body">
                <div id="rolePermissions">
                    <p class="text-muted mb-0">Select a role to see permissions</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Selected Modules</h5>
            </div>
            <div class="card-body">
                <div id="selectedModules">
                    <p class="text-muted mb-0">No modules selected</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Quick Tips</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-2">
                    <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                    <small class="text-muted">
                        <strong>Admin Role:</strong> Has full access to all modules automatically.
                    </small>
                </div>
                <div class="d-flex align-items-start mb-2">
                    <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                    <small class="text-muted">
                        <strong>View Access:</strong> User can see the module but not make changes.
                    </small>
                </div>
                <div class="d-flex align-items-start mb-2">
                    <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                    <small class="text-muted">
                        <strong>Edit Access:</strong> User can modify data in the module.
                    </small>
                </div>
                <div class="d-flex align-items-start">
                    <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                    <small class="text-muted">
                        <strong>Permissions:</strong> Can be updated later from user management.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.module-card {
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.module-card:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.module-card.selected {
    border-color: #007bff;
    background-color: #f8f9ff;
}

.form-check-input:checked + .form-check-label {
    font-weight: 500;
    color: #007bff;
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

// Role badge colors
const roleBadgeColors = {
    'admin': 'danger',
    'purchase_team': 'primary',
    'inventory_manager': 'info',
    'user': 'secondary'
};

// Role display names
const roleDisplayNames = {
    'admin': 'Administrator',
    'purchase_team': 'Purchase Team',
    'inventory_manager': 'Inventory Manager',
    'user': 'User'
};

// Update role permissions display
function updateRolePermissions(role) {
    const permissionsDiv = document.getElementById('rolePermissions');
    const moduleSection = document.getElementById('modulePermissionsSection');
    
    if (role === 'admin') {
        // Hide module permissions for admin
        moduleSection.style.display = 'none';
        permissionsDiv.innerHTML = '<p class="text-success mb-0"><i class="fas fa-crown me-1"></i> Full system access - all modules available</p>';
    } else if (role && rolePermissions[role]) {
        // Show module permissions for non-admin roles
        moduleSection.style.display = 'block';
        const permissions = rolePermissions[role];
        let html = '<ul class="list-unstyled mb-0">';
        permissions.forEach(permission => {
            html += `<li class="mb-1"><i class="fas fa-check text-success me-2"></i><small>${permission}</small></li>`;
        });
        html += '</ul>';
        permissionsDiv.innerHTML = html;
    } else {
        moduleSection.style.display = 'none';
        permissionsDiv.innerHTML = '<p class="text-muted mb-0"><small>Select a role to see permissions</small></p>';
    }
}


// Update selected modules display
function updateSelectedModules() {
    const selectedModulesDiv = document.getElementById('selectedModules');
    const checkboxes = document.querySelectorAll('.module-view:checked');
    const moduleCount = document.getElementById('moduleCount');

    moduleCount.textContent = checkboxes.length;

    if (checkboxes.length === 0) {
        selectedModulesDiv.innerHTML = '<p class="text-muted mb-0">No modules selected</p>';
    } else {
        let html = '<ul class="list-unstyled mb-0">';
        checkboxes.forEach(checkbox => {
            const label = checkbox.closest('.card-body').querySelector('h6').textContent.trim();
            html += `<li><i class="fas fa-cube text-primary me-2"></i><small>${label}</small></li>`;
        });
        html += '</ul>';
        selectedModulesDiv.innerHTML = html;
    }
}

// Update user preview
function updateUserPreview() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;
    const status = document.getElementById('status').value;

    document.getElementById('previewName').textContent = name || 'New User';
    document.getElementById('previewEmail').textContent = email || 'user@example.com';

    const roleBadge = document.getElementById('previewRole');
    if (role && roleDisplayNames[role]) {
        roleBadge.textContent = roleDisplayNames[role];
        roleBadge.className = `badge bg-${roleBadgeColors[role]} mb-2`;
    } else {
        roleBadge.textContent = 'Select Role';
        roleBadge.className = 'badge bg-secondary mb-2';
    }

    const statusBadge = document.getElementById('previewStatus');
    if (status === 'active') {
        statusBadge.textContent = 'Active';
        statusBadge.className = 'badge bg-success';
    } else {
        statusBadge.textContent = 'Inactive';
        statusBadge.className = 'badge bg-secondary';
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const moduleViewCheckboxes = document.querySelectorAll('.module-view');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const statusSelect = document.getElementById('status');

    // Trigger updates on load
    updateRolePermissions(roleSelect.value);
    updateSelectedModules();
    updateUserPreview();

    // Role change
    roleSelect.addEventListener('change', function () {
        updateRolePermissions(this.value);
        updateUserPreview();
    });

    // Module checkbox change
    document.querySelectorAll('.module-view, .module-edit').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedModules);
    });

    // Input changes
    nameInput.addEventListener('input', updateUserPreview);
    emailInput.addEventListener('input', updateUserPreview);
    statusSelect.addEventListener('change', updateUserPreview);

    // Select All / Clear All
    document.getElementById('selectAllModules').addEventListener('click', function () {
        document.querySelectorAll('#modulePermissionsSection input[type="checkbox"]').forEach(cb => cb.checked = true);
        updateSelectedModules();
    });

    document.getElementById('clearAllModules').addEventListener('click', function () {
        document.querySelectorAll('#modulePermissionsSection input[type="checkbox"]').forEach(cb => cb.checked = false);
        updateSelectedModules();
    });
});
</script>
@endsection
