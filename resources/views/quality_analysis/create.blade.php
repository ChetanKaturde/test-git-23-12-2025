@extends('layouts.app')

@section('title', 'Create Quality Analysis - INVENTORY PRO')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Input Product Details (IPD)</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quality-analysis.index') }}">Quality Analysis</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                <h5 class="mb-0">Start with Quality Checks</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('quality-analysis.store') }}">
                    @csrf

                    <!-- Step 1: Select Purchase Order -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="vendorFilter" class="form-label">Select Vendor</label>
                            <select name="vendor_filter" id="vendorFilter" class="form-select">
                                <option value="">Select from Drop Down</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="purchaseOrderSelect" class="form-label">View PO</label>
                            <select name="purchase_order_id" id="purchaseOrderSelect" class="form-select" required>
                                <option value="">Click to view</option>
                                @foreach($purchaseOrders as $po)
                                    <option 
                                        value="{{ $po->id }}" 
                                        data-vendor="{{ $po->vendor->id ?? '' }}"
                                        data-po-number="{{ $po->po_number }}"
                                        data-vendor-name="{{ $po->vendor->name ?? 'N/A' }}"
                                        data-po-date="{{ $po->created_at->format('M d, Y') }}"
                                        data-has-qa="{{ $po->hasQualityAnalysis() ? 'true' : 'false' }}"
                                        {{ isset($purchaseOrder) && $purchaseOrder->id == $po->id ? 'selected' : '' }}
                                    >
                                        {{ $po->po_number }} - {{ $po->vendor->name ?? 'N/A' }}
                                        @if($po->hasQualityAnalysis())
                                            <span class="text-warning">(QA Already Created)</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selected PO Information -->
                    <div id="purchaseOrderInfo" class="alert alert-info" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>PO Number:</strong> <span id="poNumber"></span><br>
                                <strong>Vendor:</strong> <span id="poVendor"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Date:</strong> <span id="poDate"></span><br>
                                <strong>Total Items:</strong> <span id="poItemCount"></span>
                            </div>
                        </div>
                    </div>

                    <!-- QA Already Exists Alert -->
                    <div id="qaExistsAlert" class="alert alert-warning" style="display: none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>Quality Analysis Already Exists!</strong><br>
                                This purchase order already has a quality analysis created. You cannot create another one.
                                <a href="#" id="viewExistingQA" class="btn btn-sm btn-outline-primary ms-2">View Existing QA</a>
                            </div>
                        </div>
                    </div>

                    <!-- IPD Form -->
                    <div id="ipdForm" style="display: none;">
                        <h6 class="mb-3">Product Details for Quality Analysis</h6>
                        <div id="itemsContainer">
                            <!-- Items will be loaded here -->
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fas fa-check"></i> Create Quality Analysis
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Item Template -->
<template id="itemTemplate">
    <div class="card mb-3 item-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0">Product: <span class="item-name"></span></h6>
                <small class="text-muted">Category: <span class="item-category"></span></small>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-info me-2">Qty: <span class="item-quantity"></span></span>
                <span class="badge bg-secondary dimensions-badge" style="display: none;">
                    <i class="fas fa-cube me-1"></i><span class="dimensions-text"></span>
                </span>
            </div>
        </div>
        <div class="card-body">
            <!-- Material Specifications Panel -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-light border">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-clipboard-list me-2"></i>Material Specifications
                                </h6>
                                
                                <!-- Material Dimensions -->
                                <div class="dimensions-info mb-3" style="display: none;">
                                    <div class="row dimensions-details">
                                        <div class="col-6 col-md-3 length-info" style="display: none;">
                                            <small class="text-muted d-block">Length</small>
                                            <div class="fw-bold text-primary length-value">-</div>
                                        </div>
                                        <div class="col-6 col-md-3 width-info" style="display: none;">
                                            <small class="text-muted d-block">Width</small>
                                            <div class="fw-bold text-primary width-value">-</div>
                                        </div>
                                        <div class="col-6 col-md-3 height-info" style="display: none;">
                                            <small class="text-muted d-block">Height</small>
                                            <div class="fw-bold text-primary height-value">-</div>
                                        </div>
                                        <div class="col-6 col-md-3 diameter-info" style="display: none;">
                                            <small class="text-muted d-block">Diameter</small>
                                            <div class="fw-bold text-primary diameter-value">-</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Material Information Display -->
                                <div class="mb-3">
                                    <label class="form-label small">Material Information</label>
                                    <div class="material-info-display p-2 bg-light border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><strong>Material:</strong> <span class="material-name-display">-</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><strong>Type:</strong> <span class="material-type-display">-</span></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><strong>Density:</strong> <span class="material-density-display">-</span></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Auto-calculated Expected Values -->
                                <div class="expected-calculations">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Expected Volume:</small>
                                        <span class="fw-bold text-success expected-volume-display">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Expected Weight:</small>
                                        <span class="fw-bold text-success expected-weight-display">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-exclamation-circle me-2"></i>Quality Tolerances
                                </h6>
                                <div class="tolerance-info">
                                    <div class="mb-2">
                                        <small class="text-muted">Volume Tolerance:</small>
                                        <span class="badge bg-success ms-1">±5%</span>
                                        <span class="badge bg-warning ms-1">±10%</span>
                                        <span class="badge bg-danger ms-1">>10%</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Weight Tolerance:</small>
                                        <span class="badge bg-success ms-1">±3%</span>
                                        <span class="badge bg-warning ms-1">±7%</span>
                                        <span class="badge bg-danger ms-1">>7%</span>
                                    </div>
                                    <small class="text-info">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Green = Pass, Yellow = Caution, Red = Fail
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Input Product Details (IPD) - Auto-filled from calculations -->
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-clipboard-check me-2"></i>Expected Product Details (Auto-calculated)
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">1. Product Name / Category</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" name="items[INDEX][product_name]" class="form-control" 
                                       placeholder="Product Name" required readonly>
                            </div>
                            <div class="col-6">
                                <input type="text" name="items[INDEX][product_category]" class="form-control" 
                                       placeholder="Category" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            2. Expected Volumetric Data 
                            <span class="badge bg-secondary ms-1">Auto-calculated</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="items[INDEX][expected_volumetric_data]" 
                                   class="form-control expected-volume-input" step="0.01" placeholder="0.00" 
                                   required readonly style="background-color: #e9ecef;">
                            <span class="input-group-text">cm³</span>
                            <button type="button" class="btn btn-outline-info btn-sm recalculate-btn" 
                                    title="Recalculate from dimensions">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <small class="text-info calculation-formula">Formula: -</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            3. Expected Weight 
                            <span class="badge bg-secondary ms-1">Auto-calculated</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="items[INDEX][expected_weight]" 
                                   class="form-control expected-weight-input" step="0.01" placeholder="0.00" 
                                   required readonly style="background-color: #e9ecef;">
                            <span class="input-group-text">g</span>
                            <button type="button" class="btn btn-outline-info btn-sm recalculate-weight-btn" 
                                    title="Recalculate weight">
                                <i class="fas fa-balance-scale"></i>
                            </button>
                        </div>
                        <small class="text-info weight-formula">Formula: Volume × Density</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">4. Other Analysis Parameters</label>
                        <textarea name="items[INDEX][other_analysis_parameters]" class="form-control" rows="3" 
                                  placeholder="Surface finish, color consistency, hardness, etc."></textarea>
                    </div>
                </div>

                <!-- Quality Check Results - User Input Required -->
                <div class="col-md-6">
                    <h6 class="text-success mb-3">
                        <i class="fas fa-search me-2"></i>Measured Quality Results
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Actual Volumetric Data <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="items[INDEX][actual_volumetric_data]" 
                                   class="form-control actual-volume-input" step="0.01" placeholder="Measure and enter..." 
                                   required>
                            <span class="input-group-text">cm³</span>
                        </div>
                        <div class="volume-variance-indicator mt-1" style="display: none;">
                            <small class="variance-text"></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Actual Weight <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="items[INDEX][actual_weight]" 
                                   class="form-control actual-weight-input" step="0.01" placeholder="Weigh and enter..." 
                                   required>
                            <span class="input-group-text">g</span>
                        </div>
                        <div class="weight-variance-indicator mt-1" style="display: none;">
                            <small class="variance-text"></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity Received</label>
                        <input type="number" name="items[INDEX][quantity_received]" class="form-control" 
                               step="0.01" placeholder="0.00" required readonly style="background-color: #e9ecef;">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Manufacturing Date</label>
                                <input type="date" name="items[INDEX][manufacturing_date]" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="items[INDEX][expiry_date]" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quality Assessment</label>
                        <div class="quality-status-display mb-2" style="display: none;">
                            <div class="d-flex align-items-center">
                                <span class="quality-icon me-2"></span>
                                <span class="quality-text fw-bold"></span>
                            </div>
                        </div>
                        <textarea name="items[INDEX][remarks]" class="form-control" rows="2" 
                                  placeholder="Quality inspector remarks, deviations noted, pass/fail reasoning..."></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Hidden Fields -->
            <input type="hidden" name="items[INDEX][purchase_order_item_id]" class="item-id">
            <input type="hidden" name="items[INDEX][material_id]" class="material-id">
            <input type="hidden" class="material-dimensions-data">
            <input type="hidden" class="material-density-value">
        </div>
    </div>
</template>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Vendor filter
    $('#vendorFilter').on('change', function () {
        const vendorId = $(this).val();
        const poSelect = $('#purchaseOrderSelect');

        poSelect.find('option').each(function () {
            const optionVendor = $(this).attr('data-vendor');
            const isDefault = $(this).val() === '';
            const shouldShow = !vendorId || optionVendor == vendorId || isDefault;

            $(this).toggle(shouldShow);
        });

        poSelect.val('');
        hideAllSections();
    });

    // PO selection
    $('#purchaseOrderSelect').on('change', function() {
        const poId = $(this).val();
        const selectedOption = $(this).find('option:selected');
        
        if (poId) {
            const hasQA = selectedOption.data('has-qa') === 'true';
            
            // Update PO info
            $('#poNumber').text(selectedOption.data('po-number') || 'N/A');
            $('#poVendor').text(selectedOption.data('vendor-name') || 'N/A');
            $('#poDate').text(selectedOption.data('po-date') || 'N/A');
            $('#purchaseOrderInfo').show();
            
            if (hasQA) {
                // Show warning and disable form
                $('#qaExistsAlert').show();
                $('#ipdForm').hide();
                $('#submitBtn').prop('disabled', true);
                
                // Set up view existing QA link
                $('#viewExistingQA').attr('href', `/quality-analysis/purchase-order/${poId}`);
            } else {
                // Hide warning and enable form
                $('#qaExistsAlert').hide();
                $('#submitBtn').prop('disabled', false);
                loadPurchaseOrderItems(poId);
            }
        } else {
            hideAllSections();
        }
    });

    // Auto-load PO if set on page
    @if(isset($purchaseOrder) && $purchaseOrder)
        loadPurchaseOrderItems({{ $purchaseOrder->id }});
    @endif

    // Form submission validation
    $('form').on('submit', function(e) {
        const selectedOption = $('#purchaseOrderSelect option:selected');
        const hasQA = selectedOption.data('has-qa') === 'true';
        
        if (hasQA) {
            e.preventDefault();
            alert('Cannot create quality analysis. This purchase order already has a quality analysis.');
            return false;
        }
    });
});

function hideAllSections() {
    $('#purchaseOrderInfo').hide();
    $('#qaExistsAlert').hide();
    $('#ipdForm').hide();
}

function loadItems(items) {
    const container = $('#itemsContainer');
    const template = $('#itemTemplate').html();
    container.empty();

    if (!items || items.length === 0) {
        container.html('<div class="alert alert-info">No items to display.</div>');
        return;
    }

    items.forEach((item, index) => {
        let itemHtml = template.replace(/INDEX/g, index);
        const itemCard = $(itemHtml);

        // Basic fields
        const name = item.item_name || item.material_name || 'Unnamed';
        const category = item.material_category || '';

        itemCard.find('.item-name').text(name);
        itemCard.find('.item-category').text(category);
        itemCard.find('.item-quantity').text(item.quantity || '0');
        itemCard.find('.item-id').val(item.id || '');
        itemCard.find('.material-id').val(item.material_id || '');

        // Handle material information and density
        setupMaterialInformation(itemCard, item);

        // Handle dimensions and auto-calculations
        if (item.material_dimensions) {
            setupDimensionsAndCalculations(itemCard, item.material_dimensions, item.material_density || 1.0);
        }

        // Pre-fill basic fields
        itemCard.find(`input[name="items[${index}][product_name]"]`).val(name);
        itemCard.find(`input[name="items[${index}][product_category]"]`).val(category);
        itemCard.find(`input[name="items[${index}][quantity_received]"]`).val(item.quantity || '');

        // Batch info if available
        if (item.batch_number) itemCard.find(`input[name="items[${index}][batch_number]"]`).val(item.batch_number);
        if (item.expiry_date) itemCard.find(`input[name="items[${index}][expiry_date]"]`).val(item.expiry_date);
        if (item.manufacturing_date) itemCard.find(`input[name="items[${index}][manufacturing_date]"]`).val(item.manufacturing_date);

        // Store data
        itemCard.find('.material-dimensions-data').val(JSON.stringify(item.material_dimensions || {}));
        itemCard.find('.material-density-value').val(item.material_density || 1.0);

        // Setup event handlers for this card
        setupCardEventHandlers(itemCard, index);

        container.append(itemCard);
    });
}

function setupMaterialInformation(itemCard, item) {
    // Display material information fetched from database
    const materialName = item.material_name || 'Unknown Material';
    const materialType = item.material_type || 'Unknown Type';
    const materialDensity = item.material_density || 1.0;
    
    itemCard.find('.material-name-display').text(materialName);
    itemCard.find('.material-type-display').text(materialType);
    itemCard.find('.material-density-display').text(`${materialDensity} g/cm³`);
}

function setupDimensionsAndCalculations(itemCard, dimensions, density) {
    if (!dimensions || typeof dimensions !== 'object') return;

    const dimensionsInfo = itemCard.find('.dimensions-info');
    const dimensionsBadge = itemCard.find('.dimensions-badge');
    const dimensionsText = itemCard.find('.dimensions-text');
    
    let dimensionParts = [];
    let hasDimensions = false;

    // Display dimensions dynamically
    if (dimensions.length && dimensions.length > 0) {
        itemCard.find('.length-info').show();
        itemCard.find('.length-value').text(`${dimensions.length} cm`);
        dimensionParts.push(`L:${dimensions.length}`);
        hasDimensions = true;
    }

    if (dimensions.width && dimensions.width > 0) {
        itemCard.find('.width-info').show();
        itemCard.find('.width-value').text(`${dimensions.width} cm`);
        dimensionParts.push(`W:${dimensions.width}`);
        hasDimensions = true;
    }

    if (dimensions.height && dimensions.height > 0) {
        itemCard.find('.height-info').show();
        itemCard.find('.height-value').text(`${dimensions.height} cm`);
        dimensionParts.push(`H:${dimensions.height}`);
        hasDimensions = true;
    }

    if (dimensions.diameter && dimensions.diameter > 0) {
        itemCard.find('.diameter-info').show();
        itemCard.find('.diameter-value').text(`${dimensions.diameter} cm`);
        dimensionParts.push(`D:${dimensions.diameter}`);
        hasDimensions = true;
    }

    if (hasDimensions) {
        dimensionsText.text(dimensionParts.join(' × '));
        dimensionsBadge.show();
        dimensionsInfo.show();
        
        // Auto-calculate expected values using fetched density
        calculateExpectedValues(itemCard, dimensions, density);
    }
}

function calculateExpectedValues(itemCard, dimensions, materialDensity) {
    const volume = calculateVolume(dimensions);
    const density = materialDensity || parseFloat(itemCard.find('.material-density-value').val()) || 1.0;
    const weight = volume * density;

    if (volume > 0) {
        // Update expected volume
        itemCard.find('.expected-volume-input').val(volume.toFixed(2));
        itemCard.find('.expected-volume-display').text(`${volume.toFixed(2)} cm³`);
        
        // Update calculation formula display dynamically
        let formula = '';
        if (dimensions.diameter && dimensions.height) {
            const radius = dimensions.diameter / 2;
            formula = `π × (${radius})² × ${dimensions.height} = ${volume.toFixed(2)} cm³`;
        } else if (dimensions.length && dimensions.width && dimensions.height) {
            formula = `${dimensions.length} × ${dimensions.width} × ${dimensions.height} = ${volume.toFixed(2)} cm³`;
        } else if (dimensions.length && dimensions.height && !dimensions.width) {
            formula = `${dimensions.length} × ${dimensions.length} × ${dimensions.height} = ${volume.toFixed(2)} cm³`;
        }
        itemCard.find('.calculation-formula').text(`Formula: ${formula}`);
    }

    if (weight > 0) {
        // Update expected weight
        itemCard.find('.expected-weight-input').val(weight.toFixed(2));
        itemCard.find('.expected-weight-display').text(`${weight.toFixed(2)} g`);
        
        // Update weight formula with actual values
        itemCard.find('.weight-formula').text(`Formula: ${volume.toFixed(2)} cm³ × ${density} g/cm³ = ${weight.toFixed(2)} g`);
    }
}

function calculateVolume(dimensions) {
    if (!dimensions || typeof dimensions !== 'object') return 0;

    // For cylindrical objects (has diameter)
    if (dimensions.diameter && dimensions.height) {
        const radius = dimensions.diameter / 2;
        return Math.PI * radius * radius * dimensions.height;
    }
    
    // For rectangular objects (box/cuboid)
    if (dimensions.length && dimensions.width && dimensions.height) {
        return dimensions.length * dimensions.width * dimensions.height;
    }
    
    // For square objects (length = width)
    if (dimensions.length && dimensions.height && !dimensions.width) {
        return dimensions.length * dimensions.length * dimensions.height;
    }
    
    return 0;
}

function setupCardEventHandlers(itemCard, index) {
    // Recalculate buttons
    itemCard.find('.recalculate-btn').on('click', function() {
        const dimensions = JSON.parse(itemCard.find('.material-dimensions-data').val() || '{}');
        const density = parseFloat(itemCard.find('.material-density-value').val()) || 1.0;
        calculateExpectedValues(itemCard, dimensions, density);
    });

    itemCard.find('.recalculate-weight-btn').on('click', function() {
        const dimensions = JSON.parse(itemCard.find('.material-dimensions-data').val() || '{}');
        const density = parseFloat(itemCard.find('.material-density-value').val()) || 1.0;
        calculateExpectedValues(itemCard, dimensions, density);
    });

    // Actual value inputs with variance calculation
    itemCard.find('.actual-volume-input').on('input', function() {
        calculateVarianceAndStatus(itemCard, 'volume');
    });

    itemCard.find('.actual-weight-input').on('input', function() {
        calculateVarianceAndStatus(itemCard, 'weight');
    });
}

function calculateVarianceAndStatus(itemCard, type) {
    const expectedInput = itemCard.find(type === 'volume' ? '.expected-volume-input' : '.expected-weight-input');
    const actualInput = itemCard.find(type === 'volume' ? '.actual-volume-input' : '.actual-weight-input');
    const varianceDiv = itemCard.find(type === 'volume' ? '.volume-variance-indicator' : '.weight-variance-indicator');
    
    const expected = parseFloat(expectedInput.val()) || 0;
    const actual = parseFloat(actualInput.val()) || 0;
    
    if (expected > 0 && actual > 0) {
        const variance = ((actual - expected) / expected) * 100;
        const absVariance = Math.abs(variance);
        
        // Tolerance levels
        const tolerance = type === 'volume' ? { good: 5, caution: 10 } : { good: 3, caution: 7 };
        
        let className = 'text-success';
        let icon = 'fa-check-circle';
        let status = 'PASS';
        
        if (absVariance > tolerance.caution) {
            className = 'text-danger';
            icon = 'fa-times-circle';
            status = 'FAIL';
        } else if (absVariance > tolerance.good) {
            className = 'text-warning';
            icon = 'fa-exclamation-triangle';
            status = 'CAUTION';
        }
        
        const unit = type === 'volume' ? 'cm³' : 'g';
        const direction = variance > 0 ? 'over' : 'under';
        
        varianceDiv.show().find('.variance-text')
            .html(`<i class="fas ${icon} me-1"></i><strong>${status}</strong> - ${Math.abs(variance).toFixed(1)}% ${direction} (${actual} vs ${expected} ${unit})`)
            .attr('class', `variance-text ${className}`);
    } else {
        varianceDiv.hide();
    }
    
    // Update overall quality status
    updateOverallQualityStatus(itemCard);
}

function updateOverallQualityStatus(itemCard) {
    const volumeActual = parseFloat(itemCard.find('.actual-volume-input').val()) || 0;
    const weightActual = parseFloat(itemCard.find('.actual-weight-input').val()) || 0;
    const volumeExpected = parseFloat(itemCard.find('.expected-volume-input').val()) || 0;
    const weightExpected = parseFloat(itemCard.find('.expected-weight-input').val()) || 0;
    
    const qualityDisplay = itemCard.find('.quality-status-display');
    const qualityIcon = itemCard.find('.quality-icon');
    const qualityText = itemCard.find('.quality-text');
    
    if (volumeActual > 0 && weightActual > 0 && volumeExpected > 0 && weightExpected > 0) {
        const volumeVariance = Math.abs(((volumeActual - volumeExpected) / volumeExpected) * 100);
        const weightVariance = Math.abs(((weightActual - weightExpected) / weightExpected) * 100);
        
        let overallStatus = 'PASS';
        let statusClass = 'text-success';
        let statusIcon = 'fas fa-check-circle text-success';
        
        // Determine overall status based on worst case
        if (volumeVariance > 10 || weightVariance > 7) {
            overallStatus = 'REJECT';
            statusClass = 'text-danger';
            statusIcon = 'fas fa-times-circle text-danger';
        } else if (volumeVariance > 5 || weightVariance > 3) {
            overallStatus = 'REVIEW REQUIRED';
            statusClass = 'text-warning';
            statusIcon = 'fas fa-exclamation-triangle text-warning';
        }
        
        qualityIcon.html(`<i class="${statusIcon}"></i>`);
        qualityText.text(`Quality Status: ${overallStatus}`).attr('class', `quality-text fw-bold ${statusClass}`);
        qualityDisplay.show();
    } else {
        qualityDisplay.hide();
    }
}

// Fetch PO items with batch info, dimensions, and material density
function loadPurchaseOrderItems(poId) {
    $('#itemsContainer').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><br><br>Loading items and calculating expected values from material database...</div>');

    $.ajax({
        url: `/quality-analysis/purchase-order-items/${poId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && Array.isArray(response.items) && response.items.length > 0) {
                $('#poItemCount').text(response.items.length);
                loadItems(response.items);
                $('#ipdForm').show();
                
                // Show success message
                setTimeout(() => {
                    $('#itemsContainer').prepend(`
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Ready for Quality Analysis!</strong> Expected values have been calculated using material dimensions and density from database. 
                            Please measure actual values and enter them in the right column.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }, 500);
            } else {
                $('#itemsContainer').html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>No items found for this PO.</div>');
                $('#ipdForm').show();
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error loading PO items.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            console.error('Error:', xhr.responseText);
            $('#itemsContainer').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error:</strong> ${errorMessage}
                    <br><small>Please try refreshing the page or contact system administrator.</small>
                </div>
            `);
            $('#ipdForm').show();
        }
    });
}
</script>

<style>
.item-card {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.item-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-color: #0d6efd;
}

.dimensions-badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.dimensions-details {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
}

.dimensions-details .col-6, .dimensions-details .col-md-3 {
    text-align: center;
    padding: 0.5rem;
    border-right: 1px solid #adb5bd;
}

.dimensions-details .col-6:last-child, .dimensions-details .col-md-3:last-child {
    border-right: none;
}

.material-info-display {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border: 1px solid #bbdefb !important;
}

.expected-calculations {
    background: linear-gradient(135deg, #d1edff 0%, #e7f3ff 100%);
    border-radius: 0.375rem;
    padding: 0.75rem;
    border: 1px solid #b6d7ff;
}

.tolerance-info {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-radius: 0.375rem;
    padding: 0.75rem;
    border: 1px solid #ffeaa7;
}

.variance-indicator {
    margin-top: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.quality-status-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 0.375rem;
    padding: 0.5rem;
    border: 2px solid #dee2e6;
    margin-bottom: 0.5rem;
}

.calculation-formula, .weight-formula {
    color: #6c757d;
    font-style: italic;
    font-size: 0.85rem;
}

.recalculate-btn, .recalculate-weight-btn {
    border-left: none !important;
    transition: all 0.2s ease;
}

.recalculate-btn:hover, .recalculate-weight-btn:hover {
    background-color: #e7f3ff;
    color: #0d6efd;
}

.badge.bg-success { background-color: #198754 !important; }
.badge.bg-warning { background-color: #ffc107 !important; color: #000 !important; }
.badge.bg-danger { background-color: #dc3545 !important; }
.badge.bg-info { background-color: #0dcaf0 !important; }
.badge.bg-secondary { background-color: #6c757d !important; }

.alert-light {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #dee2e6;
}

.form-control[readonly] {
    background-color: #e9ecef !important;
    opacity: 1;
}

.text-primary { color: #0d6efd !important; }
.text-success { color: #198754 !important; }
.text-warning { color: #ffc107 !important; }
.text-danger { color: #dc3545 !important; }
.text-info { color: #0dcaf0 !important; }

.fw-bold { font-weight: 700 !important; }

/* Responsive Design */
@media (max-width: 768px) {
    .dimensions-details .col-6 {
        border-right: none;
        border-bottom: 1px solid #adb5bd;
        margin-bottom: 0.5rem;
    }
    
    .dimensions-details .col-6:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .item-card .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .item-card .card-header > div {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .item-card .card-header > div:last-child {
        margin-bottom: 0;
        display: flex;
        justify-content: space-between;
    }
}

/* Animation for loading */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.fa-spinner {
    animation: pulse 1.5s infinite;
}

/* Print styles */
@media print {
    .btn, .alert-dismissible .btn-close {
        display: none !important;
    }
    
    .item-card {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
}
</style>
@endsection