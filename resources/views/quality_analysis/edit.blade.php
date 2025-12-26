@extends('layouts.app')

@section('title', 'Edit Quality Analysis - INVENTORY PRO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Quality Analysis</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quality-analysis.index') }}">Quality Analysis</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center">
            <div class="badge bg-primary me-2">Quality Analyst</div>
            <a href="{{ route('quality-analysis.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Quality Analysis</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('quality-analysis.update', $analysis->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Purchase Order Info -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>PO Number:</strong> {{ $analysis->purchaseOrder->po_number ?? 'N/A' }}<br>
                                    <strong>Vendor:</strong> {{ $analysis->purchaseOrder->vendor->name ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Date:</strong> {{ optional($analysis->purchaseOrder)->created_at?->format('M d, Y') ?? 'N/A' }}<br>
                                    <strong>Total Items:</strong> {{ $analysis->items ? $analysis->items->count() : 0 }}
                                </div>
                            </div>
                        </div>

                        <!-- Status & Date -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Analysis Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $analysis->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $analysis->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $analysis->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ $analysis->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                          
                        </div>

                        <!-- Item Section -->
                        @php
                            $item = $analysis->purchaseOrderItem;
                        @endphp

                        <div class="card mb-3 item-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Product: {{ $item->product_name ?? 'N/A' }}</h6>
                                <span class="badge bg-info">Quantity: {{ $item->quantity_received ?? 'N/A' }}</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- IPD -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Input Product Details (IPD)</h6>

                                        <div class="mb-3">
                                            <label class="form-label">1. Product Name / Category</label>
                                           <div class="row">
    <div class="col-6">
        <input type="text" class="form-control" value="{{ $analysis->product_name ?? 'N/A' }}" readonly>
    </div>
    <div class="col-6">
        <input type="text" class="form-control" value="{{ $analysis->product_category ?? 'N/A' }}" readonly>
    </div>
</div>

                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">2. Expected Volumetric Data</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="expected_volumetric_data" class="form-control" value="{{ $analysis->expected_volumetric_data }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">3. Expected Weight</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="expected_weight" class="form-control" value="{{ $analysis->expected_weight }}">
                                                <span class="input-group-text">g</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">4. Other Analysis Parameter</label>
                                            <textarea name="other_analysis_parameters" class="form-control">{{ $analysis->other_analysis_parameters }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Results -->
                                    <div class="col-md-6">
                                        <h6 class="text-success mb-3">Quality Check Results</h6>

                                        <div class="mb-3">
                                            <label class="form-label">Actual Volumetric Data</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="actual_volumetric_data" class="form-control" value="{{ $analysis->actual_volumetric_data }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Actual Weight</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="actual_weight" class="form-control" value="{{ $analysis->actual_weight }}">
                                                <span class="input-group-text">g</span>
                                            </div>
                                        </div>

                                       <div class="mb-3">
    <label class="form-label">Quantity Received</label>
    <input type="number" class="form-control" value="{{ $analysis->quantity_received ?? '0.00' }}" readonly>
</div>


                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Manufacturing Date</label>
                                                <input type="date" name="manufacturing_date" class="form-control" value="{{ $analysis->manufacturing_date ? $analysis->manufacturing_date->format('Y-m-d') : '' }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Expiry Date</label>
                                                <input type="date" name="expiry_date" class="form-control" value="{{ $analysis->expiry_date ? $analysis->expiry_date->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea name="remarks" class="form-control">{{ $analysis->remarks }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">General Analysis Notes</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Additional Notes</label>
                                    <textarea name="notes" class="form-control" rows="4">{{ $analysis->notes ?? '' }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="analyst_name" class="form-label">Analyst Name</label>
                                        <input type="text" name="analyst_name" class="form-control" value="{{ $analysis->analyst_name ?? auth()->user()->name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="approval_status" class="form-label">Approval Status</label>
                                        <select name="approval_status" class="form-select">
                                            <option value="pending" {{ $analysis->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $analysis->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $analysis->approval_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('quality-analysis.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <a href="{{ route('quality-analysis.show', $analysis->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update Quality Analysis
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Auto-calculate variance when actual values are entered
    $('input[name*="[actual_volumetric_data]"], input[name*="[actual_weight]"]').on('input', function() {
        calculateVariance($(this));
    });

    // Status change validation
    $('#status').on('change', function() {
        const status = $(this).val();
        const analysisDate = $('#analysis_date');
        
        if (status === 'completed' && !analysisDate.val()) {
            analysisDate.prop('required', true);
            alert('Please set an analysis date for completed analysis.');
        } else {
            analysisDate.prop('required', false);
        }
    });

    // Form validation before submit
    $('form').on('submit', function(e) {
        const status = $('#status').val();
        const analysisDate = $('#analysis_date').val();
        
        if (status === 'completed' && !analysisDate) {
            e.preventDefault();
            alert('Analysis date is required for completed analysis.');
            $('#analysis_date').focus();
            return false;
        }
        
        // Check if at least one actual value is entered for completed status
        if (status === 'completed') {
            let hasActualData = false;
            $('input[name*="[actual_volumetric_data]"], input[name*="[actual_weight]"]').each(function() {
                if ($(this).val()) {
                    hasActualData = true;
                    return false;
                }
            });
            
            if (!hasActualData) {
                e.preventDefault();
                alert('Please enter at least some actual test results for completed analysis.');
                return false;
            }
        }
    });
});

function calculateVariance(element) {
    const itemCard = element.closest('.item-card');
    const name = element.attr('name');
    
    if (name.includes('volumetric_data')) {
        const expected = parseFloat(itemCard.find('input[name*="[expected_volumetric_data]"]').val()) || 0;
        const actual = parseFloat(element.val()) || 0;
        const variance = expected > 0 ? ((actual - expected) / expected * 100).toFixed(2) : 0;
        
        // You can add a variance display field here if needed
        console.log('Volumetric variance:', variance + '%');
    } else if (name.includes('weight')) {
        const expected = parseFloat(itemCard.find('input[name*="[expected_weight]"]').val()) || 0;
        const actual = parseFloat(element.val()) || 0;
        const variance = expected > 0 ? ((actual - expected) / expected * 100).toFixed(2) : 0;
        
        // You can add a variance display field here if needed
        console.log('Weight variance:', variance + '%');
    }
}

// Add visual indicators for status
function updateStatusIndicators() {
    const status = $('#status').val();
    const statusBadge = $('.badge.bg-primary');
    
    switch(status) {
        case 'pending':
            statusBadge.removeClass('bg-primary bg-success bg-danger bg-warning')
                      .addClass('bg-warning').text('Pending');
            break;
        case 'in_progress':
            statusBadge.removeClass('bg-primary bg-success bg-danger bg-warning')
                      .addClass('bg-primary').text('In Progress');
            break;
        case 'completed':
            statusBadge.removeClass('bg-primary bg-success bg-danger bg-warning')
                      .addClass('bg-success').text('Completed');
            break;
        case 'rejected':
            statusBadge.removeClass('bg-primary bg-success bg-danger bg-warning')
                      .addClass('bg-danger').text('Rejected');
            break;
    }
}

// Initialize status indicators
updateStatusIndicators();
$('#status').on('change', updateStatusIndicators);
</script>
@endsection