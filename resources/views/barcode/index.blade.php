@extends('layouts.app')

@section('title', 'Barcode Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h3 mb-0">🎯 Barcode Management</h2>
                <div class="btn-group">
                    <a href="{{ route('barcode.dashboard') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar me-1"></i> Dashboard
                    </a>
                  <!--  <a href="{{ route('barcode.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Generate Barcodes
                    </a>-->
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('barcode.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Barcode, Material, Supplier..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Material</label>
                            <select name="material_id" class="form-select">
                                <option value="">All Materials</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" 
                                            {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Expiry</label>
                            <select name="expiry_filter" class="form-select">
                                <option value="">All Items</option>
                                <option value="expired" {{ request('expiry_filter') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="expiring_soon" {{ request('expiry_filter') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Barcodes Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Barcode List ({{ $barcodes->total() }} total)</h5>
                </div>
                <div class="card-body p-0">
                    @if($barcodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Barcode</th>
                                        <th>Material</th>
                                        <th>Batch</th>
                                        <th>Supplier</th>
                                        <th>Quantity</th>
                                        <th>Expiry</th>
                                        <th>Status</th>
                                        <th>Scans</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barcodes as $barcode)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="barcode_ids[]" value="{{ $barcode->id }}">
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $barcode->barcode_number }}</div>
                                                <small class="text-muted">{{ $barcode->material_code }}</small>
                                            </td>
                                            <td>
                                                <div>{{ $barcode->material_name }}</div>
                                                <small class="text-muted">Grade: {{ $barcode->quality_grade }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $barcode->batch->batch_number ?? 'N/A' }}</span>
                                            </td>
                                            <td>  @if($barcode->purchaseOrder && $barcode->purchaseOrder->vendor)
                                       {{ $barcode->purchaseOrder->vendor->name }} - {{ $barcode->purchaseOrder->vendor->phone }}
                                        @else
                                      N/A
                                          @endif
                                           </td>
                                            <td>
                                                <div>{{ $barcode->quantity }} units</div>
                                                <small class="text-muted">{{ $barcode->weight }} kg</small>
                                            </td>
                                            <td>
                                                @if($barcode->expiry_date)
                                                    @php
                                                        $isExpired = $barcode->expiry_date->isPast();
                                                        $isExpiringSoon = $barcode->expiry_date->diffInDays(now()) <= 7;
                                                    @endphp
                                                    <div class="{{ $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : '') }}">
                                                        {{ $barcode->expiry_date->format('d M, Y') }}
                                                    </div>
                                                    @if($isExpired)
                                                        <small class="text-danger">Expired</small>
                                                    @elseif($isExpiringSoon)
                                                        <small class="text-warning">Expiring Soon</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No expiry</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'inactive' => 'secondary',
                                                        'damaged' => 'danger',
                                                        'expired' => 'warning'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$barcode->status] ?? 'secondary' }}">
                                                    {{ ucfirst($barcode->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $barcode->print_count ?? 0 }} prints</div>
                                                @if($barcode->last_scanned_at)
                                                    <small class="text-muted">
                                                        Last: {{ $barcode->last_scanned_at->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">Never scanned</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('barcode.show', $barcode) }}" 
                                                       class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('barcode.batch-print', ['ids' => $barcode->id]) }}" 
                                                       class="btn btn-outline-success" title="Print">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <a href="{{ route('barcode.edit', $barcode) }}" 
                                                       class="btn btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $barcodes->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-barcode fa-3x text-muted mb-3"></i>
                            <h5>No barcodes found</h5>
                            <p class="text-muted">Generate your first barcode to get started.</p>
                            <a href="{{ route('barcode.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Generate Barcode
                            </a>
                        </div>
                    @endif
            
        </div>
    </div>

    <!-- Bulk Actions -->
  <!---  @if($barcodes->count() > 0)
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="bulkActionForm" method="POST" action="{{ route('barcode.bulk-action') }}">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Bulk Actions</label>
                                    <select name="action" class="form-select" required>
                                        <option value="">Select Action</option>
                                        <option value="activate">Activate Selected</option>
                                        <option value="deactivate">Deactivate Selected</option>
                                        <option value="mark_damaged">Mark as Damaged</option>
                                        <option value="delete">Delete Selected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-warning" disabled id="bulkActionBtn">
                                        <i class="fas fa-cogs me-1"></i> Apply to Selected
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <span id="selectedCount">0</span> items selected
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
           </div>
    @endif
</div> --->

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').change(function() {
        $('input[name="barcode_ids[]"]').prop('checked', this.checked);
        updateBulkActions();
    });

    // Individual checkbox change
    $('input[name="barcode_ids[]"]').change(function() {
        updateBulkActions();
    });

    // Update bulk actions state
    function updateBulkActions() {
        const checkedCount = $('input[name="barcode_ids[]"]:checked').length;
        $('#selectedCount').text(checkedCount);
        $('#bulkActionBtn').prop('disabled', checkedCount === 0);
        
        // Update form with selected IDs
        const selectedIds = [];
        $('input[name="barcode_ids[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        // Remove existing hidden inputs
        $('#bulkActionForm input[name="barcode_ids[]"]').remove();
        
        // Add selected IDs as hidden inputs
        selectedIds.forEach(function(id) {
            $('#bulkActionForm').append('<input type="hidden" name="barcode_ids[]" value="' + id + '">');
        });
    }

    // Bulk action form submission
    $('#bulkActionForm').submit(function(e) {
        const action = $('select[name="action"]').val();
        const count = $('input[name="barcode_ids[]"]:checked').length;
        
        if (count === 0) {
            e.preventDefault();
            alert('Please select at least one barcode.');
            return;
        }

        const confirmMessages = {
            'activate': `Are you sure you want to activate ${count} barcode(s)?`,
            'deactivate': `Are you sure you want to deactivate ${count} barcode(s)?`,
            'mark_damaged': `Are you sure you want to mark ${count} barcode(s) as damaged?`,
            'delete': `Are you sure you want to delete ${count} barcode(s)? This action cannot be undone.`
        };

        if (!confirm(confirmMessages[action])) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection
