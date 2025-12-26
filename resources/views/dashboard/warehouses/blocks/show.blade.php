@extends('layouts.app')
@section('title', 'Block Details - ' . $block->name)

@section('content')

<div class="container mt-4">
    <!-- Block Information Card -->
    <div class="card shadow rounded mb-4">
        <div class="card-header bg-info text-black">
            <h4 class="mb-0 ">Block Details - {{ $block->name }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Warehouse:</strong> {{ $warehouse->name }}</p>
                    <p><strong>Block Name:</strong> {{ $block->name }}</p>
                    <p><strong>Dimensions:</strong> {{ $block->rows }} rows × {{ $block->columns }} columns</p>
                    <p><strong>Total Slots:</strong> {{ $block->slots->count() }}</p>
                </div>
                <div class="col-md-6">
                   <p><strong>Empty Slots:</strong> 
                        <span class="badge bg-success">{{ $block->slots->where('status', 'empty')->count() }}</span>
                    </p>
                    <p><strong>Partial Slots:</strong> 
                        <span class="badge bg-warning">{{ $block->slots->where('status', 'partial')->count() }}</span>
                    </p>
                    <p><strong>Full Slots:</strong> 
                        <span class="badge bg-info">{{ $block->slots->where('status', 'full')->count() }}</span>
                    </p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('warehouses.blocks.edit', [$warehouse->id, $block->id]) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Block
                </a>
                <a href="{{ route('warehouses.blocks.index', $warehouse->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Blocks
                </a>
            </div>
        </div>
    </div>

    <!-- Slots Grid Card -->
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-black">
            <h5 class="mb-0 ">Slots Layout</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Row/Col</th>
                            @for($c = 1; $c <= $block->columns; $c++)
                                <th>{{ $c }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @for($r = 1; $r <= $block->rows; $r++)
                            <tr>
                                <td class="fw-bold">{{ $r }}</td>
                                @for($c = 1; $c <= $block->columns; $c++)
                                    @php
                                        $slot = $block->slots->where('row', $r)->where('column', $c)->first();
                                        $statusClass = '';
                                        $statusText = '';

                                        if ($slot) {
                                            switch ($slot->status) {
                                                case 'empty':
                                                    $statusClass = 'table-success';
                                                    $statusText = 'Empty';
                                                    break;
                                                case 'partial':
                                                    $statusClass = 'table-warning';
                                                    $statusText = 'Partial';
                                                    break;
                                                case 'full':
                                                    $statusClass = 'table-info';
                                                    $statusText = 'Full';
                                                    break;
                                                default:
                                                    $statusClass = 'table-light';
                                                    $statusText = 'Unknown';
                                                    break;
                                            }
                                        } else {
                                            $statusClass = 'table-secondary';
                                            $statusText = 'No Slot';
                                        }
                                    @endphp
                                    <td class="{{ $statusClass }}" style="cursor: pointer;"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Row {{ $r }}, Column {{ $c }} - {{ $statusText }}"
                                        onclick="showSlotDetails({{ $slot->id ?? 'null' }})">
                                        <small>{{ $r }}-{{ $c }}</small>
                                        <br>
                                        <span class="badge bg-light text-dark">{{ $statusText }}</span>
                                        @if($slot && $slot->batch && $slot->batch->material)
                                            <br><small class="text-muted">{{ Str::limit($slot->batch->material->name, 10) }}</small>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend Card -->
    <div class="card shadow rounded mt-4">
        <div class="card-header bg-secondary text-black">
            <h6 class="mb-0">Status Legend</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <span class="badge bg-success">Empty</span> - Slot is unused
                </div>
                <div class="col-md-3">
                    <span class="badge bg-warning">Partial</span> - Slot is partially filled
                </div>
                <div class="col-md-3">
                    <span class="badge bg-info">Full</span> - Slot is fully occupied
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="slotDetailsModal" tabindex="-1" aria-labelledby="slotDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="slotDetailsModalLabel">
                    <i class="fas fa-info-circle"></i> Slot Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="slotDetailsContent">
                <!-- Dynamic content goes here -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading slot details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Bootstrap 5 JS -->

<!-- Custom Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips for Bootstrap 5
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    console.log('Bootstrap 5 loaded');
    console.log('Tooltips initialized');

    // ✅ Initialize modal ONCE here
    const modalElement = document.getElementById('slotDetailsModal');
    slotModal = new bootstrap.Modal(modalElement);

    // Modal event listeners (optional)
    modalElement.addEventListener('show.bs.modal', function () {
        console.log('Modal is opening');
    });

    modalElement.addEventListener('shown.bs.modal', function () {
        console.log('Modal is fully shown');
    });
});


function showSlotDetails(slotId) {
    console.log('showSlotDetails called with:', slotId);
    
    if (!slotId) {
        alert('No slot details available.');
        return;
    }

    // Show loading spinner
    document.getElementById('slotDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading slot details...</p>
        </div>
    `;

    // ✅ Show modal (use the already-created instance)
    slotModal.show();

    // Fetch data
    fetch(`/slot-details/${slotId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Fetch success:', data);
        displaySlotDetails(data);
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showError('Failed to fetch slot details. Please try again.');
    });
}


function displaySlotDetails(response) {
    if (response.success && response.slot) {
        const slot = response.slot;

        let html = `
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-start border-primary border-4">
                        <div class="card-body">
                            <h6 class="text-primary"><i class="fas fa-box"></i> Material Information</h6>
                            <p><strong>Material Name:</strong> ${slot.product_name ?? 'N/A'}</p>
                            <p><strong>Quantity:</strong> ${slot.quantity ?? 'N/A'}</p>
                            <p><strong>Batch Number:</strong> ${slot.batch_number ?? 'N/A'}</p>
                            <p><strong>Warehouse Name:</strong> ${slot.warehouse_name ?? 'N/A'}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-start border-success border-4">
                        <div class="card-body">
                            <h6 class="text-success"><i class="fas fa-map-marker-alt"></i> Slot Information</h6>
                            <p><strong>Slot ID:</strong> ${slot.id ?? 'N/A'}</p>
                            <p><strong>Position:</strong> Row ${slot.row ?? 'N/A'}, Column ${slot.column ?? 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${getStatusBadgeClass(slot.status)}">${slot.status ?? 'N/A'}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-start border-info border-4">
                        <div class="card-body">
                            <h6 class="text-info"><i class="fas fa-warehouse"></i> Block Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Block ID:</strong> ${slot.block?.id ?? 'N/A'}</p>
                                    <p><strong>Block Name:</strong> ${slot.block?.name ?? 'N/A'}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Dimensions:</strong> ${slot.block?.rows ?? 'N/A'} rows × ${slot.block?.columns ?? 'N/A'} columns</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('slotDetailsContent').innerHTML = html;
    } else {
        showError('Slot details not found.');
    }
}

function showError(message) {
    document.getElementById('slotDetailsContent').innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> ${message}
        </div>
    `;
}

function getStatusBadgeClass(status) {
    switch (status) {
        case 'empty': return 'success';
        case 'partial': return 'warning';
        case 'full': return 'info';
        default: return 'secondary';
    }
}

// Test function to manually trigger modal
function testModal() {
    const modal = new bootstrap.Modal(document.getElementById('slotDetailsModal'));
     slotModal.show();
}
</script>
@endpush