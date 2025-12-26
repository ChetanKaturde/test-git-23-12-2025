@extends('layouts.app')

@section('title', 'Quality Analysis Details - KAIZEN 360')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Quality Analysis Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quality-analysis.index') }}">Quality Analysis</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('quality-analysis.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            @if($analysis->quality_status == 'pending')
                <a href="{{ route('quality-analysis.edit', $analysis->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
            @endif
            @if($analysis->quality_status == 'approved' && $analysis->barcode)
                <button type="button" class="btn btn-info" onclick="printBarcode()">
                    <i class="fas fa-print"></i> Print Barcode
                </button>
                <button type="button" class="btn btn-success" onclick="downloadBarcodePDF()">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Main Details Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Analysis Details</h5>
                    <span class="badge bg-{{ $analysis->status_badge }} fs-6">
                        {{ ucfirst($analysis->quality_status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Product Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Product Name:</td>
                                    <td>{{ $analysis->product_name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Category:</td>
                                    <td>{{ $analysis->product_category }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Quantity Received:</td>
                                    <td>{{ $analysis->quantity_received }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Manufacturing Date:</td>
                                    <td>{{ $analysis->manufacturing_date ? $analysis->manufacturing_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Expiry Date:</td>
                                    <td>{{ $analysis->expiry_date ? $analysis->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                @if($analysis->sku_id)
                                <tr>
                                    <td class="fw-bold">SKU ID:</td>
                                    <td><code>{{ $analysis->sku_id }}</code></td>
                                </tr>
                                @endif
                                @if($analysis->barcode)
                                <tr>
                                    <td class="fw-bold">Barcode:</td>
                                    <td><code>{{ $analysis->barcode }}</code></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">Quality Parameters</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Expected Volumetric Data:</td>
                                    <td>{{ $analysis->expected_volumetric_data }}%</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Actual Volumetric Data:</td>
                                    <td>
                                        @if($analysis->actual_volumetric_data)
                                            {{ $analysis->actual_volumetric_data }}%
                                            @if($analysis->actual_volumetric_data != $analysis->expected_volumetric_data)
                                                <span class="badge bg-warning ms-2">Variance</span>
                                            @else
                                                <span class="badge bg-success ms-2">Match</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Not tested</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Expected Weight:</td>
                                    <td>{{ $analysis->expected_weight }}g</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Actual Weight:</td>
                                    <td>
                                        @if($analysis->actual_weight)
                                            {{ $analysis->actual_weight }}g
                                            @if($analysis->actual_weight != $analysis->expected_weight)
                                                <span class="badge bg-warning ms-2">Variance</span>
                                            @else
                                                <span class="badge bg-success ms-2">Match</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Not tested</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Variance Percentage:</td>
                                    <td>
                                        @if($analysis->actual_weight && $analysis->expected_weight)
                                            @php
                                                $variance = (($analysis->actual_weight - $analysis->expected_weight) / $analysis->expected_weight) * 100;
                                            @endphp
                                            <span class="badge bg-{{ abs($variance) > 5 ? 'danger' : (abs($variance) > 2 ? 'warning' : 'success') }}">
                                                {{ number_format($variance, 2) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($analysis->other_analysis_parameters)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-info mb-3">Other Analysis Parameters</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $analysis->other_analysis_parameters }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($analysis->remarks)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-secondary mb-3">Remarks</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $analysis->remarks }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($analysis->quality_status == 'rejected' && $analysis->rejected_reason)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-danger mb-3">Rejection Reason</h6>
                            <div class="bg-danger bg-opacity-10 p-3 rounded border border-danger">
                                {{ $analysis->rejected_reason }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Purchase Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Purchase Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">PO Number:</td>
                                    <td>
                                        <a href="{{ route('purchase-orders.show', $analysis->purchaseOrder->id) }}" class="text-decoration-none">
                                            {{ $analysis->purchaseOrder->po_number }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Vendor:</td>
                                    <td>
                                        {{ $analysis->purchaseOrder->vendor->name ?? 'N/A' }}
                                        @if($analysis->purchaseOrder->vendor->business_name)
                                            <br><small class="text-muted">({{ $analysis->purchaseOrder->vendor->business_name }})</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">PO Date:</td>
                                    <td>{{ $analysis->purchaseOrder->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Item Name:</td>
                                    <td>{{ $analysis->purchaseOrderItem->item_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ordered Quantity:</td>
                                    <td>{{ $analysis->purchaseOrderItem->quantity ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Unit Price:</td>
                                    <td>₹{{ number_format($analysis->purchaseOrderItem->unit_price ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <div>
                            <span class="badge bg-{{ $analysis->status_badge }} fs-6">
                                {{ ucfirst($analysis->quality_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Created By</label>
                        <div>{{ $analysis->createdBy->name ?? 'System' }}</div>
                        <small class="text-muted">{{ $analysis->created_at->format('M d, Y \a\t g:i A') }}</small>
                    </div>

                    @if($analysis->approved_by)
                    <div class="mb-3">
                        <label class="form-label">
                            {{ $analysis->quality_status == 'approved' ? 'Approved By' : 'Reviewed By' }}
                        </label>
                        <div>{{ $analysis->approvedBy->name ?? 'N/A' }}</div>
                        <small class="text-muted">{{ $analysis->approved_at ? $analysis->approved_at->format('M d, Y \a\t g:i A') : 'N/A' }}</small>
                    </div>
                    @endif

                    @if($analysis->quality_status == 'pending')
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Barcode Card -->
            @if($analysis->barcode)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Barcode Information</h5>
                </div>
                <div class="card-body text-center">
                    <div id="barcode-container" class="mb-3">
                        <!-- Barcode Number Display -->
                        <div class="fw-bold mb-2" style="font-size: 14px; letter-spacing: 2px;">
                            {{ $analysis->barcode }}
                        </div>
                        <!-- Barcode Image -->
                        <canvas id="barcode-canvas" style="max-width: 100%;"></canvas>
                        <!-- Barcode Number Below -->
                        <div class="mt-2">
                            <code style="font-size: 12px;">{{ $analysis->barcode }}</code>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="printBarcode()">
                            <i class="fas fa-print"></i> Print Barcode
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadBarcodePDF()">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
               <div class="card-body">
    <div class="d-grid gap-2">
        <a href="{{ route('quality-analysis.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list"></i> View All Analysis
        </a>

        @if($analysis->quality_status == 'pending')
            <a href="{{ route('quality-analysis.edit', $analysis->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit"></i> Edit Analysis
            </a>
        @endif

        <a href="{{ route('purchase-orders.show', $analysis->purchaseOrder->id) }}" class="btn btn-outline-info">
            <i class="fas fa-file-alt"></i> View Purchase Order
        </a>
    </div>

    <!-- Centered Delete Button -->
    <div class="text-center mt-3">
        <form action="{{ route('quality-analysis.destroy', $analysis->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this analysis?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </form>
    </div>
</div>

            </div>
        </div>
    </div>
</div>

<!-- Hidden PDF Content -->
<div id="pdf-content" style="display: none;">
    <div style="max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px;">
            <h1 style="color: #333; margin: 0;">KAIZEN 360</h1>
            <h2 style="color: #666; margin: 10px 0;">Quality Analysis Report</h2>
        </div>

        <!-- Barcode Section -->
        <div style="text-align: center; margin-bottom: 30px; border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
            <div style="font-weight: bold; font-size: 16px; margin-bottom: 10px;">{{ $analysis->barcode }}</div>
            <canvas id="pdf-barcode-canvas" style="max-width: 300px;"></canvas>
            <div style="font-size: 12px; margin-top: 10px;">{{ $analysis->barcode }}</div>
        </div>

        <!-- Product Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Product Information</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 30%;">Product Name:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->product_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Category:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->product_category }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Quantity Received:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->quantity_received }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Manufacturing Date:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->manufacturing_date ? $analysis->manufacturing_date->format('M d, Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Expiry Date:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->expiry_date ? $analysis->expiry_date->format('M d, Y') : 'N/A' }}</td>
                </tr>
                @if($analysis->sku_id)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">SKU ID:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->sku_id }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Quality Parameters -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Quality Parameters</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 30%;">Expected Volumetric Data:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->expected_volumetric_data }}%</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Actual Volumetric Data:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">
                        @if($analysis->actual_volumetric_data)
                            {{ $analysis->actual_volumetric_data }}%
                        @else
                            Not tested
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Expected Weight:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->expected_weight }}g</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Actual Weight:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">
                        @if($analysis->actual_weight)
                            {{ $analysis->actual_weight }}g
                        @else
                            Not tested
                        @endif
                    </td>
                </tr>
                @if($analysis->actual_weight && $analysis->expected_weight)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Variance Percentage:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">
                        @php
                            $variance = (($analysis->actual_weight - $analysis->expected_weight) / $analysis->expected_weight) * 100;
                        @endphp
                        {{ number_format($variance, 2) }}%
                    </td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Purchase Order Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Purchase Order Information</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 30%;">PO Number:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrder->po_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Vendor:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrder->vendor->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">PO Date:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrder->created_at->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Item Name:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrderItem->item_name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        @if($analysis->other_analysis_parameters)
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Other Analysis Parameters</h3>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                {{ $analysis->other_analysis_parameters }}
            </div>
        </div>
        @endif

        @if($analysis->remarks)
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Remarks</h3>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                {{ $analysis->remarks }}
            </div>
        </div>
        @endif

        <!-- Status Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Status Information</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 30%;">Status:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ ucfirst($analysis->quality_status) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Created By:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->createdBy->name ?? 'System' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Created At:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->created_at->format('M d, Y \a\t g:i A') }}</td>
                </tr>
                @if($analysis->approved_by)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Approved By:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $analysis->approvedBy->name ?? 'N/A' }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            <p>Generated on {{ now()->format('M d, Y \a\t g:i A') }}</p>
            <p>KAIZEN 360 - Quality Management System</p>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if($analysis->quality_status == 'pending')
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quality-analysis.approve', $analysis->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Quality Analysis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Are you sure you want to approve this quality analysis? This action will generate SKU and barcode for the product.
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Approval Remarks (Optional)</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter any additional remarks..."></textarea>
                    </div>
                    <input type="hidden" name="approved_items[]" value="{{ $analysis->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quality-analysis.reject', $analysis->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Quality Analysis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Are you sure you want to reject this quality analysis? Please provide a reason for rejection.
                    </div>
                    <div class="mb-3">
                        <label for="rejected_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejected_reason" id="rejected_reason" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Include Required Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate barcode on page load
    @if($analysis->barcode)
    generateBarcode('{{ $analysis->barcode }}');
    @endif
});

function generateBarcode(barcodeValue) {
    // Generate barcode for main display
    const canvas = document.getElementById('barcode-canvas');
    if (canvas) {
        JsBarcode(canvas, barcodeValue, {
            format: "CODE128",
            width: 2,
            height: 100,
            displayValue: false,
            margin: 10,
            fontSize: 12,
            textMargin: 5
        });
    }

    // Generate barcode for PDF
    const pdfCanvas = document.getElementById('pdf-barcode-canvas');
    if (pdfCanvas) {
        JsBarcode(pdfCanvas, barcodeValue, {
            format: "CODE128",
            width: 2,
            height: 80,
            displayValue: false,
            margin: 10,
            fontSize: 12,
            textMargin: 5
        });
    }
}

function printBarcode() {
    const printWindow = window.open('', '_blank');

    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Quality Analysis Report - {{ $analysis->barcode }}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    line-height: 1.4;
                    color: #333;
                }
                .print-container {
                    max-width: 800px;
                    margin: 0 auto;
                    text-align: center;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                }
                .header h1 {
                    color: #333;
                    margin: 0;
                    font-size: 24px;
                }
                .header h2 {
                    color: #666;
                    margin: 10px 0;
                    font-size: 18px;
                }
                .barcode-section {
                    text-align: center;
                    margin: 30px 0;
                    border: 2px solid #333;
                    padding: 20px;
                    background: #f9f9f9;
                }
                .barcode-number {
                    font-weight: bold;
                    font-size: 18px;
                    margin-bottom: 15px;
                    letter-spacing: 3px;
                }
                .barcode-number-small {
                    font-size: 14px;
                    margin-top: 10px;
                    letter-spacing: 2px;
                }
                .info-section {
                    margin: 30px 0;
                    text-align: left;
                }
                .info-title {
                    font-size: 16px;
                    font-weight: bold;
                    color: #333;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 10px;
                    margin-bottom: 15px;
                    text-align: center;
                }
                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .info-table td {
                    padding: 8px 12px;
                    border-bottom: 1px solid #eee;
                    vertical-align: top;
                }
                .info-table td:first-child {
                    font-weight: bold;
                    width: 35%;
                    background: #f8f9fa;
                }
                .status-badge {
                    display: inline-block;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: bold;
                    text-transform: uppercase;
                }
                .status-approved { background: #d4edda; color: #155724; }
                .status-pending { background: #fff3cd; color: #856404; }
                .status-rejected { background: #f8d7da; color: #721c24; }
                .footer {
                    text-align: center;
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 1px solid #ddd;
                    color: #666;
                    font-size: 12px;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="print-container">
                <!-- Header -->
                <div class="header">
                    <h1>KAIZEN 360</h1>
                    <h2>Quality Analysis Report</h2>
                </div>

                <!-- Barcode Section -->
                <div class="barcode-section">
                    <div class="barcode-number">{{ $analysis->barcode }}</div>
                    <canvas id="print-barcode"></canvas>
                    <div class="barcode-number-small">{{ $analysis->barcode }}</div>
                </div>

                <!-- Product Information -->
                <div class="info-section">
                    <div class="info-title">Product Information</div>
                    <table class="info-table">
                        <tr>
                            <td>Product Name:</td>
                            <td>{{ $analysis->product_name }}</td>
                        </tr>
                        <tr>
                            <td>Category:</td>
                            <td>{{ $analysis->product_category }}</td>
                        </tr>
                        <tr>
                            <td>Quantity Received:</td>
                            <td>{{ $analysis->quantity_received }}</td>
                        </tr>
                        <tr>
                            <td>Manufacturing Date:</td>
                            <td>{{ $analysis->manufacturing_date ? $analysis->manufacturing_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Expiry Date:</td>
                            <td>{{ $analysis->expiry_date ? $analysis->expiry_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @if($analysis->sku_id)
                        <tr>
                            <td>SKU ID:</td>
                            <td>{{ $analysis->sku_id }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Quality Parameters -->
                <div class="info-section">
                    <div class="info-title">Quality Parameters</div>
                    <table class="info-table">
                        <tr>
                            <td>Expected Volumetric Data:</td>
                            <td>{{ $analysis->expected_volumetric_data }}%</td>
                        </tr>
                        <tr>
                            <td>Actual Volumetric Data:</td>
                            <td>
                                @if($analysis->actual_volumetric_data)
                                    {{ $analysis->actual_volumetric_data }}%
                                @else
                                    Not tested
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Expected Weight:</td>
                            <td>{{ $analysis->expected_weight }}g</td>
                        </tr>
                        <tr>
                            <td>Actual Weight:</td>
                            <td>
                                @if($analysis->actual_weight)
                                    {{ $analysis->actual_weight }}g
                                @else
                                    Not tested
                                @endif
                            </td>
                        </tr>
                        @if($analysis->actual_weight && $analysis->expected_weight)
                        <tr>
                            <td>Variance Percentage:</td>
                            <td>
                                @php
                                    $variance = (($analysis->actual_weight - $analysis->expected_weight) / $analysis->expected_weight) * 100;
                                @endphp
                                {{ number_format($variance, 2) }}%
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Purchase Order Information -->
                <div class="info-section">
                    <div class="info-title">Purchase Order Information</div>
                    <table class="info-table">
                        <tr>
                            <td>PO Number:</td>
                            <td>{{ $analysis->purchaseOrder->po_number }}</td>
                        </tr>
                        <tr>
                            <td>Vendor:</td>
                            <td>{{ $analysis->purchaseOrder->vendor->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>PO Date:</td>
                            <td>{{ $analysis->purchaseOrder->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td>Item Name:</td>
                            <td>{{ $analysis->purchaseOrderItem->item_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Ordered Quantity:</td>
                            <td>{{ $analysis->purchaseOrderItem->quantity ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Unit Price:</td>
                            <td>₹{{ number_format($analysis->purchaseOrderItem->unit_price ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Status Information -->
                <div class="info-section">
                    <div class="info-title">Status Information</div>
                    <table class="info-table">
                        <tr>
                            <td>Status:</td>
                            <td>
                                <span class="status-badge status-{{ $analysis->quality_status }}">
                                    {{ ucfirst($analysis->quality_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Created By:</td>
                            <td>{{ $analysis->createdBy->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <td>Created At:</td>
                            <td>{{ $analysis->created_at->format('M d, Y \\a\\t g:i A') }}</td>
                        </tr>
                        @if($analysis->approved_by)
                        <tr>
                            <td>{{ $analysis->quality_status == 'approved' ? 'Approved By' : 'Reviewed By' }}:</td>
                            <td>{{ $analysis->approvedBy->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $analysis->quality_status == 'approved' ? 'Approved At' : 'Reviewed At' }}:</td>
                            <td>{{ $analysis->approved_at ? $analysis->approved_at->format('M d, Y \\a\\t g:i A') : 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                @if($analysis->other_analysis_parameters)
                <div class="info-section">
                    <div class="info-title">Other Analysis Parameters</div>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; text-align: left;">
                        {{ $analysis->other_analysis_parameters }}
                    </div>
                </div>
                @endif

                @if($analysis->remarks)
                <div class="info-section">
                    <div class="info-title">Remarks</div>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; text-align: left;">
                        {{ $analysis->remarks }}
                    </div>
                </div>
                @endif

                @if($analysis->quality_status == 'rejected' && $analysis->rejected_reason)
                <div class="info-section">
                    <div class="info-title">Rejection Reason</div>
                    <div style="background: #f8d7da; padding: 15px; border-radius: 5px; text-align: left; border: 1px solid #f5c6cb;">
                        {{ $analysis->rejected_reason }}
                    </div>
                </div>
                @endif

                <!-- Footer -->
                <div class="footer">
                    <p>Generated on {{ now()->format('M d, Y \\a\\t g:i A') }}</p>
                    <p>KAIZEN 360 - Quality Management System</p>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"><\/script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const canvas = document.getElementById('print-barcode');
                    JsBarcode(canvas, '{{ $analysis->barcode }}', {
                        format: "CODE128",
                        width: 2,
                        height: 80,
                        displayValue: false,
                        margin: 10
                    });
                    setTimeout(() => {
                        window.print();
                        window.close();
                    }, 1000);
                });
            <\/script>
        </body>
        </html>
    `;

    printWindow.document.write(printContent);
    printWindow.document.close();
}

async function downloadBarcodePDF() {
    try {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        button.disabled = true;

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');

        // Create a temporary container for PDF content
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'absolute';
        tempContainer.style.left = '-9999px';
        tempContainer.style.top = '0';
        tempContainer.style.width = '800px';
        tempContainer.style.backgroundColor = '#ffffff';
        tempContainer.style.fontFamily = 'Arial, sans-serif';
        tempContainer.style.padding = '20px';
        tempContainer.style.lineHeight = '1.4';
        tempContainer.style.color = '#333';

        tempContainer.innerHTML = `
            <div style="max-width: 800px; margin: 0 auto;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px;">
                    <h1 style="color: #333; margin: 0; font-size: 28px;">KAIZEN 360</h1>
                    <h2 style="color: #666; margin: 10px 0; font-size: 20px;">Quality Analysis Report</h2>
                </div>

                <!-- Barcode Section -->
                <div style="text-align: center; margin: 30px 0; border: 2px solid #333; padding: 20px; background: #f9f9f9;">
                    <div style="font-weight: bold; font-size: 18px; margin-bottom: 15px; letter-spacing: 3px;">{{ $analysis->barcode }}</div>
                    <canvas id="pdf-barcode-canvas-temp" style="max-width: 300px;"></canvas>
                    <div style="font-size: 14px; margin-top: 10px; letter-spacing: 2px;">{{ $analysis->barcode }}</div>
                </div>

                <!-- Product Information -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Product Information</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 35%; background: #f8f9fa;">Product Name:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->product_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Category:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->product_category }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Quantity Received:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->quantity_received }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Manufacturing Date:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->manufacturing_date ? $analysis->manufacturing_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Expiry Date:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->expiry_date ? $analysis->expiry_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @if($analysis->sku_id)
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">SKU ID:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->sku_id }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Quality Parameters -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Quality Parameters</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 35%; background: #f8f9fa;">Expected Volumetric Data:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->expected_volumetric_data }}%</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Actual Volumetric Data:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                @if($analysis->actual_volumetric_data)
                                    {{ $analysis->actual_volumetric_data }}%
                                @else
                                    Not tested
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Expected Weight:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->expected_weight }}g</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Actual Weight:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                @if($analysis->actual_weight)
                                    {{ $analysis->actual_weight }}g
                                @else
                                    Not tested
                                @endif
                            </td>
                        </tr>
                        @if($analysis->actual_weight && $analysis->expected_weight)
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Variance Percentage:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                @php
                                    $variance = (($analysis->actual_weight - $analysis->expected_weight) / $analysis->expected_weight) * 100;
                                @endphp
                                {{ number_format($variance, 2) }}%
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Purchase Order Information -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Purchase Order Information</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 35%; background: #f8f9fa;">PO Number:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrder->po_number }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Vendor:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrder->vendor->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">PO Date:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrder->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Item Name:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrderItem->item_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Ordered Quantity:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->purchaseOrderItem->quantity ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Unit Price:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">₹{{ number_format($analysis->purchaseOrderItem->unit_price ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Status Information -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Status Information</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 35%; background: #f8f9fa;">Status:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ ucfirst($analysis->quality_status) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Created By:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->createdBy->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">Created At:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->created_at->format('M d, Y \\a\\t g:i A') }}</td>
                        </tr>
                        @if($analysis->approved_by)
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">{{ $analysis->quality_status == 'approved' ? 'Approved By' : 'Reviewed By' }}:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->approvedBy->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; background: #f8f9fa;">{{ $analysis->quality_status == 'approved' ? 'Approved At' : 'Reviewed At' }}:</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $analysis->approved_at ? $analysis->approved_at->format('M d, Y \\a\\t g:i A') : 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                @if($analysis->other_analysis_parameters)
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Other Analysis Parameters</h3>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; text-align: left;">
                        {{ $analysis->other_analysis_parameters }}
                    </div>
                </div>
                @endif

                @if($analysis->remarks)
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Remarks</h3>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; text-align: left;">
                        {{ $analysis->remarks }}
                    </div>
                </div>
                @endif

                @if($analysis->quality_status == 'rejected' && $analysis->rejected_reason)
                <div style="margin: 30px 0;">
                    <h3 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; text-align: center; font-size: 16px; margin-bottom: 20px;">Rejection Reason</h3>
                    <div style="background: #f8d7da; padding: 15px; border-radius: 5px; text-align: left; border: 1px solid #f5c6cb;">
                        {{ $analysis->rejected_reason }}
                    </div>
                </div>
                @endif

                <!-- Footer -->
                <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
                    <p>Generated on {{ now()->format('M d, Y \\a\\t g:i A') }}</p>
                    <p>KAIZEN 360 - Quality Management System</p>
                </div>
            </div>
        `;

        document.body.appendChild(tempContainer);

        // Generate barcode
        const canvas = tempContainer.querySelector('#pdf-barcode-canvas-temp');
        JsBarcode(canvas, '{{ $analysis->barcode }}', {
            format: "CODE128",
            width: 2,
            height: 80,
            displayValue: false,
            margin: 10
        });

        // Wait for barcode to render
        await new Promise(resolve => setTimeout(resolve, 500));

        // Generate PDF
        const canvasResult = await html2canvas(tempContainer, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            width: 800,
            height: tempContainer.scrollHeight
        });

        // Remove temporary container
        document.body.removeChild(tempContainer);

        // Add to PDF
        const imgWidth = 210;
        const pageHeight = 295;
        const imgHeight = (canvasResult.height * imgWidth) / canvasResult.width;
        let heightLeft = imgHeight;
        let position = 0;

        pdf.addImage(canvasResult.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(canvasResult.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        const fileName = `quality_analysis_{{ $analysis->barcode }}_${new Date().toISOString().split('T')[0]}.pdf`;
        pdf.save(fileName);

        button.innerHTML = originalText;
        button.disabled = false;

    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');

        const button = event.target;
        button.innerHTML = '<i class="fas fa-download"></i> Download PDF';
        button.disabled = false;
    }
}
</script>



@endsection