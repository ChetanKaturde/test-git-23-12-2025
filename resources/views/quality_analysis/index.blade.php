@extends('layouts.app')

@section('title', 'Quality Analysis - INVENTORY PRO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Quality Analysis</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Quality Analysis</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center">
            <div class="badge bg-primary me-2">Quality Analyst</div>
            <a href="{{ route('quality-analysis.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Start with Quality Checks
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Pending</h5>
                            <h3 class="mb-0">{{ $summary['pending'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Approved</h5>
                            <h3 class="mb-0">{{ $summary['approved'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Rejected</h5>
                            <h3 class="mb-0">{{ $summary['rejected'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total</h5>
                            <h3 class="mb-0">{{ $summary['total'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('quality-analysis.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Select Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('quality-analysis.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quality Analysis List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Quality Analysis Records</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" onclick="bulkApprove()">
                    <i class="fas fa-check"></i> Approve Selected
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="generateBarcodes()">
                    <i class="fas fa-barcode"></i> Generate Barcodes
                </button>
               <!-- <button type="button" class="btn btn-info btn-sm" onclick="printBarcodes()">
                    <i class="fas fa-print"></i> Print Barcodes
                </button> -->
            </div>
        </div>
        <div class="card-body">
            <form id="bulkActionForm" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Product Name</th>
                                <th>Lot % Weight</th>
                                <th>Updated</th>
                                <th>Approved</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analyses as $analysis)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_items[]" value="{{ $analysis->id }}" class="form-check-input item-checkbox">
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $analysis->product_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $analysis->purchaseOrder->po_number ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-secondary">{{ $analysis->expected_volumetric_data }}%</span>
                                            <span class="badge bg-secondary">{{ $analysis->expected_weight }}g</span>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $analysis->updated_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($analysis->quality_status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @elseif($analysis->quality_status == 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $analysis->status_badge }}">
                                            {{ ucfirst($analysis->quality_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('quality-analysis.show', $analysis->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($analysis->quality_status == 'pending')
                                                <a href="{{ route('quality-analysis.edit', $analysis->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="approveItem({{ $analysis->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="rejectItem({{ $analysis->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                            <p>No quality analysis records found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="card-footer">
            {{ $analyses->links() }}
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Approve Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('quality-analysis.bulk-approve') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="bulk_remarks" class="form-control" rows="3"></textarea>
                    </div>
                    <div id="selectedItemsContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Selected Items</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejected_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Single Item Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="approveForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Approval Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="approved_items[]" id="approveItemId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk approve function
function bulkApprove() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    if (selectedItems.length === 0) {
        alert('Please select at least one item to approve.');
        return;
    }
    
    // Add selected items to modal
    const container = document.getElementById('selectedItemsContainer');
    container.innerHTML = '';
    selectedItems.forEach(item => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_items[]';
        input.value = item.value;
        container.appendChild(input);
    });
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('bulkApproveModal'));
    modal.show();
}

// Generate barcodes function
function generateBarcodes() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    if (selectedItems.length === 0) {
        alert('Please select at least one approved item to generate barcodes.');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("quality-analysis.generate-barcodes") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    selectedItems.forEach(item => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_items[]';
        input.value = item.value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Print barcodes function
function printBarcodes() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    if (selectedItems.length === 0) {
        alert('Please select at least one approved item to print barcodes.');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("quality-analysis.print-barcodes") }}';
    form.target = '_blank';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    selectedItems.forEach(item => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_items[]';
        input.value = item.value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Approve single item
function approveItem(id) {
    document.getElementById('approveItemId').value = id;
    document.getElementById('approveForm').action = `/quality-analysis/${id}/approve`;
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

// Reject single item
function rejectItem(id) {
    document.getElementById('rejectForm').action = `/quality-analysis/${id}/reject`;
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endsection