@extends('layouts.app')

@section('title', 'Assign Staff - ' . $warehouse->name)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Staff Assignment</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.warehouses.index') }}">Warehouses</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.warehouses.show', $warehouse) }}">{{ $warehouse->name }}</a></li>
                            <li class="breadcrumb-item active">Staff Assignment</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('dashboard.warehouses.show', $warehouse) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Warehouse
                </a>
            </div>
        </div>
    </div>

    <!-- Warehouse Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-1">{{ $warehouse->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $warehouse->address }}, {{ $warehouse->city }}, {{ $warehouse->state }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'danger' }} fs-6">
                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($warehouse->is_default)
                                <span class="badge bg-primary fs-6 ms-2">Default</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Add New Staff Section -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Assign New Staff
                    </h5>
                </div>
                <div class="card-body">
                    @if($availableUsers->count() > 0)
                        <form id="assignStaffForm" action="{{ route('dashboard.warehouses.store-staff', $warehouse) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select Staff Member</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                    <option value="">Choose a staff member...</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Select role...</option>
                                    @foreach($roles as $roleKey => $roleLabel)
                                        <option value="{{ $roleKey }}" {{ old('role') == $roleKey ? 'selected' : '' }}>
                                            {{ $roleLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>Assign Staff
                            </button>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                            <h6 class="mt-3 text-muted">No Available Staff</h6>
                            <p class="text-muted small">All active users are already assigned to this warehouse.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Current Staff Section -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Current Staff ({{ $assignedUsers->count() }})
                        </h5>
                        @if($assignedUsers->count() > 0)
                            <small class="text-muted">Click on roles to edit</small>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($assignedUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedUsers as $user)
                                        <tr id="staff-row-{{ $user->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <select class="form-select form-select-sm role-select" 
                                                        data-user-id="{{ $user->id }}" 
                                                        data-warehouse-id="{{ $warehouse->id }}">
                                                    @foreach($roles as $roleKey => $roleLabel)
                                                        <option value="{{ $roleKey }}" {{ $user->pivot->role == $roleKey ? 'selected' : '' }}>
                                                            {{ $roleLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-danger btn-sm remove-staff-btn" 
                                                        data-user-id="{{ $user->id }}" 
                                                        data-warehouse-id="{{ $warehouse->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        title="Remove staff">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-slash text-muted" style="font-size: 3rem;"></i>
                            <h6 class="mt-3 text-muted">No Staff Assigned</h6>
                            <p class="text-muted">Start by assigning staff members to this warehouse.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Remove Staff Confirmation Modal -->
<div class="modal fade" id="removeStaffModal" tabindex="-1" aria-labelledby="removeStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="removeStaffModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Removal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove <strong id="staffMemberName"></strong> from this warehouse?</p>
                <p class="text-muted small mb-0">This action cannot be undone. The staff member will lose access to this warehouse.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemoveBtn">Remove Staff</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .avatar-sm {
        flex-shrink: 0;
    }
    
    .role-select {
        min-width: 130px;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-top: none;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Handle role changes
    $('.role-select').on('change', function() {
        const userId = $(this).data('user-id');
        const warehouseId = $(this).data('warehouse-id');
        const newRole = $(this).val();
        const selectElement = $(this);
        const originalRole = selectElement.find('option:selected').siblings('[selected]').val() || selectElement.find('option:first').val();
        
        // Disable the select while processing
        selectElement.prop('disabled', true);
        
        $.ajax({
            url: `/dashboard/warehouses/${warehouseId}/staff/${userId}/role`,
            method: 'PATCH',
            data: {
                role: newRole,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    // Update the selected attribute
                    selectElement.find('option').removeAttr('selected');
                    selectElement.find(`option[value="${newRole}"]`).attr('selected', 'selected');
                } else {
                    showAlert('error', response.error || 'Failed to update role');
                    // Revert to original role
                    selectElement.val(originalRole);
                }
            },
            error: function(xhr) {
                console.error('Error updating role:', xhr);
                showAlert('error', 'An error occurred while updating the role');
                // Revert to original role
                selectElement.val(originalRole);
            },
            complete: function() {
                selectElement.prop('disabled', false);
            }
        });
    });
    
    // Handle staff removal
    let userToRemove = null;
    let warehouseToUpdate = null;
    
    $('.remove-staff-btn').on('click', function() {
        userToRemove = $(this).data('user-id');
        warehouseToUpdate = $(this).data('warehouse-id');
        const userName = $(this).data('user-name');
        
        $('#staffMemberName').text(userName);
        $('#removeStaffModal').modal('show');
    });
    
    $('#confirmRemoveBtn').on('click', function() {
        if (userToRemove && warehouseToUpdate) {
            const button = $(this);
            const originalText = button.text();
            
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Removing...');
            
            $.ajax({
                url: `/dashboard/warehouses/${warehouseToUpdate}/staff/${userToRemove}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        // Remove the row from table
                        $(`#staff-row-${userToRemove}`).fadeOut(300, function() {
                            $(this).remove();
                            // Check if table is empty
                            if ($('tbody tr').length === 0) {
                                location.reload(); // Reload to show empty state
                            }
                        });
                        $('#removeStaffModal').modal('hide');
                    } else {
                        showAlert('error', response.error || 'Failed to remove staff');
                    }
                },
                error: function(xhr) {
                    console.error('Error removing staff:', xhr);
                    showAlert('error', 'An error occurred while removing the staff member');
                },
                complete: function() {
                    button.prop('disabled', false).text(originalText);
                }
            });
        }
    });
    
    // Handle form submission
    $('#assignStaffForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Assigning...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    // Reload page to update both sections
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('error', response.error || 'Failed to assign staff');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Error assigning staff:', xhr);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Please fix the following errors:\n';
                    Object.keys(errors).forEach(key => {
                        errorMessage += `• ${errors[key][0]}\n`;
                    });
                    showAlert('error', errorMessage);
                } else {
                    showAlert('error', 'An error occurred while assigning staff');
                }
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Alert function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of the container
        $('.container-fluid').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
@endpush