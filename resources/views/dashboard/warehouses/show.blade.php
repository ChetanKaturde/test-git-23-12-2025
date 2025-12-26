@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Warehouse Details: {{ $warehouse->name }}</h1>
        <div>
            <a href="{{ route('dashboard.warehouses.edit', $warehouse) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
           @can('assignStaff', $warehouse)
    <a href="{{ route('dashboard.warehouses.assign-staff', $warehouse) }}" class="btn btn-success">
        <i class="fas fa-users"></i> Manage Staff
    </a>
@endcan

            <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Warehouse Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>
                                      <span class="badge badge-info text-dark" style="font-size: 1rem;">
                               {{ $warehouse->name }} </span>
                                  </td>
                                </tr>
                                <tr>
    <td><strong>Type:</strong></td>
    <td>
        <span class="badge badge-info text-dark" style="font-size: 1rem;">
            {{ $warehouse->type_display }}
        </span>
    </td>
</tr>
<tr>
    <td><strong>Status:</strong></td>
    <td>
        <span class="badge badge-{{ $warehouse->is_active ? 'success' : 'secondary' }} text-dark" style="font-size: 1rem;">
            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>
</tr>
<tr>
    <td><strong>Default:</strong></td>
    <td>
        @if($warehouse->is_default)
            <span class="badge badge-warning text-dark" style="font-size: 1rem;">
                <i class="fas fa-star"></i> Default Warehouse
            </span>
        @else
            <span class="text-muted" style="font-size: 1rem;">No</span>
        @endif
    </td>
</tr>

                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $warehouse->created_at->format('M d, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $warehouse->updated_at->format('M d, Y g:i A') }}</td>
                                </tr>
                               <tr>
    <td><strong>Assigned Staff:</strong></td>
    <td>
        <span class="badge badge-primary">{{ $warehouse->users->count() }}</span>

    <a href="{{ route('dashboard.warehouses.assign-staff', $warehouse) }}" 
       class="btn btn-sm btn-outline-success ml-2">Manage</a>


    </td>
</tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Location Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Full Address:</strong></p>
                            <address class="ml-3">
                                {{ $warehouse->address }}<br>
                             {{ $warehouse->city }}, {{ $warehouse->state }}<br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle functionality
    $('.status-toggle').change(function() {
        const warehouseId = $(this).data('warehouse-id');
        const isActive = $(this).is(':checked');
        const status = isActive ? 'active' : 'inactive';
        
        $.ajax({
            url: `/dashboard/warehouses/${warehouseId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: status
            },
            success: function(response) {
                if (response.success) {
                    const badge = $(this).siblings('label').find('.badge');
                    badge.removeClass('badge-success badge-secondary')
                         .addClass(isActive ? 'badge-success' : 'badge-secondary')
                         .text(isActive ? 'Active' : 'Inactive');
                    
                    toastr.success(response.message);
                }
            }.bind(this),
            error: function() {
                $(this).prop('checked', !isActive);
                toastr.error('Failed to update warehouse status');
            }.bind(this)
        });
    });

    // Delete warehouse functionality
    $('.delete-warehouse').click(function() {
        const warehouseId = $(this).data('warehouse-id');
        const warehouseName = $(this).data('warehouse-name');
        
        $('#warehouse-name').text(warehouseName);
        $('#delete-form').attr('action', `/dashboard/warehouses/${warehouseId}`);
        $('#deleteModal').modal('show');
    });
});
</script>
@endpush
@endsection