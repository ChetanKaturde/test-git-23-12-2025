@extends('layouts.app')

@section('title', 'Warehouse Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Warehouse Management</h1>
      
        <a href="{{ route('dashboard.warehouses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Warehouse
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Warehouses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Default</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['default'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   @php
    $paginatedWarehouses = $warehouses;
@endphp

<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.warehouses.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search warehouses..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="city" class="form-control">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Warehouses Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Warehouses List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Default</th>
                        <th>Staff</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
             @forelse($warehouses as $warehouse)
                  <tr>
                            <td><strong>{{ $warehouse->name }}</strong></td>
                            <td>
                                <div class="text-sm">
                                    {{ $warehouse->address }}<br>
                                    <span class="text-muted">{{ $warehouse->city }}, {{ $warehouse->state }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info text-dark">{{ $warehouse->type_display }}</span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    @if($warehouse->contact_phone)
                                        <div><i class="fas fa-phone fa-sm"></i> {{ $warehouse->contact_phone }}</div>
                                    @endif
                                    @if($warehouse->contact_email)
                                        <div><i class="fas fa-envelope fa-sm"></i> {{ $warehouse->contact_email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <label class="custom-control-label" for="status{{ $warehouse->id }}">
                                        <span class="badge badge-{{ $warehouse->is_active ? 'success' : 'secondary' }} text-dark">
                                            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                @if($warehouse->is_default)
                                    <span class="badge badge-warning text-dark">
                                        <i class="fas fa-star"></i> Default
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-primary text-dark">
                                    {{ $warehouse->users_count ?? $warehouse->users->count() }}
                                </span>
                            </td>
                          <td>
    <div class="btn-group" role="group">
      
{{-- View Button --}}
@canAccess('view', 'warehouses')
    <a href="{{ route('dashboard.warehouses.show', $warehouse) }}"
       class="btn btn-info btn-sm" title="View">
        <i class="fas fa-eye"></i>
    </a>
@endcanAccess

{{-- Edit Button --}}
@canAccess('edit', 'warehouses')
    <a href="{{ route('dashboard.warehouses.edit', $warehouse) }}"
       class="btn btn-warning btn-sm" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
@endcanAccess

{{-- Assign Staff Button --}}
@canAccess('assign', 'warehouses')
    <a href="{{ route('dashboard.warehouses.assign-staff', $warehouse) }}"
       class="btn btn-success btn-sm" title="Assign Staff">
        <i class="fas fa-users"></i>
    </a>
@endcanAccess

{{-- Delete Button --}}
@canAccess('delete', 'warehouses')
    <form method="POST" action="{{ route('dashboard.warehouses.destroy', $warehouse) }}"
          style="display: inline;" class="delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@endcanAccess

    </div>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No warehouses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $warehouses->links() }}  {{-- ✅ Correct --}}
</div>


    </div>
</div>


  @foreach($warehouses as $warehouse)
        <div class="card mb-4">
            <div class="card-header">
                <h4>{{ $warehouse->name }}</h4>
            </div>
            <div class="card-body">
                @foreach($warehouse->blocks as $block)
                    <h5 class="mt-3">{{ $block->name }} ({{ $block->rows }} x {{ $block->columns }})</h5>
                    <div class="d-flex flex-wrap" style="width: fit-content;">
                        @foreach($block->slots as $slot)
                            @php
                                $color = match($slot->status) {
                                    'empty' => 'btn-success',
                                    'partial' => 'btn-warning',
                                    'full' => 'btn-danger',
                                    default => 'btn-secondary'
                                };
                            @endphp
                            <button class="btn {{ $color }} m-1" style="width: 50px; height: 50px;" title="Row: {{ $slot->row }}, Col: {{ $slot->column }}">
                                {{ $slot->row }}x{{ $slot->column }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

<!-- Updated Modal Section in your Blade file -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete warehouse <strong id="warehouse-name"></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
<!-- Delete Button in Modal -->
<form method="POST" action="{{ route('dashboard.warehouses.destroy', $warehouse) }}" 
      style="display: inline;" 
      onsubmit="return confirm('Are you sure you want to delete {{ $warehouse->name }}?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</form>

      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- Updated JavaScript Section -->
<script>
$(document).ready(function() {
    // When delete button is clicked
    $(document).on('click', '.delete-warehouse', function(e) {
        e.preventDefault();

        const warehouseId = $(this).data('warehouse-id');
        const warehouseName = $(this).data('warehouse-name');

        console.log('Warehouse ID:', warehouseId); // Debug log
        console.log('Warehouse Name:', warehouseName); // Debug log

        if (!warehouseId) {
            alert("Error: Warehouse ID is missing.");
            return;
        }

        // Set modal warehouse name
        $('#warehouse-name').text(warehouseName || 'Unknown');

        // Set delete URL - Make sure this route exists in your routes file
        const deleteUrl = `/dashboard/warehouses/${warehouseId}`;
        $('#delete-warehouse-form').attr('action', deleteUrl);
        
        console.log('Delete URL:', deleteUrl); // Debug log
        
        // Show modal
        $('#deleteModal').modal('show');
    });

    // Handle actual form submission
    $('#delete-warehouse-form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('Deleting...');
        
        // Optional: Add a small delay to show the loading state
        setTimeout(() => {
            // Form will submit normally after this
        }, 100);
    });

    // Reset modal form when closed
    $('#deleteModal').on('hidden.bs.modal', function() {
        $('#delete-warehouse-form button[type="submit"]').prop('disabled', false).text('Delete');
        $('#delete-warehouse-form').attr('action', ''); // Clear action
    });
});
</script>

<!-- Alternative: Direct form submission without modal (simpler approach) -->
<script>
// If you prefer a simple confirm dialog instead of modal:
$(document).on('click', '.delete-warehouse-simple', function(e) {
    e.preventDefault();
    
    const warehouseId = $(this).data('warehouse-id');
    const warehouseName = $(this).data('warehouse-name');
    
    if (confirm(`Are you sure you want to delete warehouse "${warehouseName}"? This action cannot be undone.`)) {
        // Create a form dynamically and submit it
        const form = $('<form method="POST" action="/dashboard/warehouses/' + warehouseId + '">' +
                      '@csrf' +
                      '@method("DELETE")' +
                      '</form>');
        $('body').append(form);
        form.submit();
    }
});
</script>
@endpush
