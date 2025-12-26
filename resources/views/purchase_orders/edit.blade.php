@extends('layouts.app')
@section('title', 'Edit Purchase Order')

@section('content')
<!-- Add CSRF token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Edit Purchase Order #{{ $purchaseOrder->po_number }}</h2>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div id="global-error-message" class="alert alert-danger d-none" role="alert"></div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST" id="purchaseOrderForm">
        @csrf
        @method('PUT')

        <!-- PO Details -->
        <div class="card mb-4">
            <div class="card-header">Purchase Order Details</div>
            <div class="card-body">
                <div class="row">
                    <!-- Vendor -->
                    <div class="col-md-6 mb-3">
                        <label for="vendor_id" class="form-label">Vendor *</label>
                        <select name="vendor_id" id="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $purchaseOrder->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PO Date -->
                    <div class="col-md-6 mb-3">
                        <label for="po_date" class="form-label">PO Date *</label>
                        <input type="date" name="po_date" id="po_date" class="form-control @error('po_date') is-invalid @enderror" 
                               value="{{ old('po_date', $purchaseOrder->po_date ? $purchaseOrder->po_date->format('Y-m-d') : '') }}" required>
                        @error('po_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order Date -->
                    <div class="col-md-6 mb-3">
                        <label for="order_date" class="form-label">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" class="form-control @error('order_date') is-invalid @enderror" 
                               value="{{ old('order_date', $purchaseOrder->order_date ? $purchaseOrder->order_date->format('Y-m-d') : '') }}" required>
                        @error('order_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expected Delivery -->
                    <div class="col-md-6 mb-3">
                        <label for="expected_delivery" class="form-label">Expected Delivery</label>
                        <input type="date" name="expected_delivery" id="expected_delivery" class="form-control @error('expected_delivery') is-invalid @enderror" 
                               value="{{ old('expected_delivery', $purchaseOrder->expected_delivery ? $purchaseOrder->expected_delivery->format('Y-m-d') : '') }}">
                        @error('expected_delivery')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Shipping Address -->
                    <div class="col-md-6 mb-3">
                        <label for="shipping_address" class="form-label">Shipping Address *</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address', $purchaseOrder->shipping_address) }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Order Status *</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            @php
                                $statuses = ['pending', 'approved', 'shipped', 'delivered', 'cancelled'];
                                $selectedStatus = old('status', $purchaseOrder->status);
                            @endphp
                            @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption }}" {{ $selectedStatus == $statusOption ? 'selected' : '' }}>
                                    {{ ucfirst($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Materials</span>
                <button type="button" class="btn btn-sm btn-outline-success" id="addMaterialRow">+ Add Material</button>
            </div>
            <div class="card-body" id="materialsContainer">
                @if($purchaseOrder->items && $purchaseOrder->items->count() > 0)
                    @foreach($purchaseOrder->items as $index => $item)
                        <div class="row material-row mb-2" data-index="{{ $index }}">
                            <div class="col-md-3">
                                <label class="form-label">Material *</label>
                                <select name="items[{{ $index }}][material_id]" class="form-select material-select" required>
                                    <option value="">Select Material</option>
                                </select>
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <input type="hidden" class="selected-material-id" value="{{ $item->material_id }}">
                                <input type="hidden" class="selected-material-name" value="{{ $item->material ? $item->material->name : '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit Price *</label>
                                <input type="number" name="items[{{ $index }}][unit_price]" class="form-control unit-price" 
                                       step="0.01" value="{{ old('items.'.$index.'.unit_price', $item->unit_price) }}" readonly required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Available Qty</label>
                                <input type="number" class="form-control available-quantity" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Order Qty *</label>
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control order-quantity" 
                                       min="1" step="0.01" value="{{ old('items.'.$index.'.quantity', $item->quantity) }}" required>
                                <div class="text-danger error-message mt-1 small" style="display: none;"></div>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">GST %</label>
                                <input type="number" name="items[{{ $index }}][gst_rate]" class="form-control gst-rate" 
                                       min="0" max="100" step="0.01" value="{{ old('items.'.$index.'.gst_rate', $item->gst_rate) }}" readonly>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Total</label>
                                <input type="number" name="items[{{ $index }}][total_price]" class="form-control total" 
                                       value="{{ old('items.'.$index.'.total_price', $item->total_price) }}" readonly>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default empty row if no items exist -->
                    <div class="row material-row mb-2" data-index="0">
                        <div class="col-md-3">
                            <label class="form-label">Material *</label>
                            <select name="items[0][material_id]" class="form-select material-select" required>
                                <option value="">Select Material</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Price *</label>
                            <input type="number" name="items[0][unit_price]" class="form-control unit-price" step="0.01" readonly required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Available Qty</label>
                            <input type="number" class="form-control available-quantity" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Order Qty *</label>
                            <input type="number" name="items[0][quantity]" class="form-control order-quantity" min="1" step="0.01" required>
                            <div class="text-danger error-message mt-1 small" style="display: none;"></div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">GST %</label>
                            <input type="number" name="items[0][gst_rate]" class="form-control gst-rate" min="0" max="100" step="0.01" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Total</label>
                            <input type="number" name="items[0][total_price]" class="form-control total" readonly>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">Order Summary</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="total_amount" class="form-label">Subtotal</label>
                        <input type="number" name="total_amount" id="total_amount" class="form-control" 
                               value="{{ old('total_amount', $purchaseOrder->total_amount) }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="gst_amount" class="form-label">GST Amount</label>
                        <input type="number" name="gst_amount" id="gst_amount" class="form-control" 
                               value="{{ old('gst_amount', $purchaseOrder->gst_amount) }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="final_amount" class="form-label">Final Amount</label>
                        <input type="number" name="final_amount" id="final_amount" class="form-control" 
                               value="{{ old('final_amount', $purchaseOrder->final_amount) }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Purchase Order</button>
            <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="btn btn-info">View Details</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let materialsData = [];
    let materialRowIndex = {{ $purchaseOrder->items ? $purchaseOrder->items->count() : 0 }};
    let existingItemsData = @json(($purchaseOrder->items ?? collect())->keyBy('material_id'));

    // Debug: Log the existing items data
    console.log('Existing items data:', existingItemsData);
    console.log('Material row index:', materialRowIndex);

    updateRemoveButtons();
    updateOrderTotals();

    // Load materials for the selected vendor on page load
    const selectedVendorId = $('#vendor_id').val();
    console.log('Selected vendor ID on load:', selectedVendorId);
    
    if (selectedVendorId) {
        fetchVendorMaterials(selectedVendorId, true);
    }

    $('#vendor_id').change(function() {
        const vendorId = $(this).val();
        if (!vendorId) {
            materialsData = [];
            resetMaterialFields();
            return;
        }
        fetchVendorMaterials(vendorId);
    });

    function fetchVendorMaterials(vendorId, isInitialLoad = false) {
        const url = `/api/vendors/${vendorId}/materials`;
        $('.material-select').html('<option value="">Loading...</option>').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
            },
            success: function(response) {
                console.log('Materials response:', response);
                materialsData = Array.isArray(response) ? response : (response.data || response.materials || []);
                
                if (materialsData.length === 0) {
                    $('.material-select').html('<option value="">No materials available</option>').prop('disabled', true);
                    return;
                }
                
                if (isInitialLoad) {
                    populateMaterialSelectsForEdit();
                } else {
                    resetMaterialFields();
                    populateMaterialSelects();
                }
                updateRemoveButtons();
            },
            error: function(xhr) {
                console.error('Error fetching materials:', xhr);
                showGlobalError('Error fetching materials. Please try again.');
                if (!isInitialLoad) {
                    resetMaterialFields();
                }
            }
        });
    }
function populateMaterialSelectsForEdit() {
    console.log('Populating material selects for edit...');

    let hasAvailableMaterial = false;

    $('.material-row').each(function() {
        const $row = $(this);
        const $select = $row.find('.material-select');
        const selectedMaterialId = $row.find('.selected-material-id').val();
        const selectedMaterialName = $row.find('.selected-material-name').val();

        console.log('Processing row with material ID:', selectedMaterialId, 'Name:', selectedMaterialName);

        $select.html('<option value="">Select Material</option>').prop('disabled', false);

        materialsData.forEach(material => {
            const isSelected = material.id == selectedMaterialId;
            const availableQty = material.remaining_qty || 0;

            if (availableQty > 0) hasAvailableMaterial = true;

            $select.append(`
                <option 
                    value="${material.id}" 
                    data-price="${material.unit_price}" 
                    data-gst="${material.gst_rate || 0}" 
                    data-quantity="${availableQty}"
                    ${isSelected ? 'selected' : ''}>
                 ${material.name} (Available: ${availableQty})
                </option>
            `);
        });

        if (selectedMaterialId) {
            $select.val(selectedMaterialId);
            updateAvailableQuantityForRow($row);

            if ($select.val() !== selectedMaterialId) {
                $select.append(`
                    <option value="${selectedMaterialId}" selected disabled>
                        ${selectedMaterialName || 'Material not available'} (Not Available)
                    </option>
                `);
                $select.val(selectedMaterialId);
            }
        }
    });

    // Show error if no materials are available
    if (!hasAvailableMaterial) {
        showMaterialErrorAndDisableSubmit();
    }
}

function populateMaterialSelects() {
    let hasAvailableMaterial = false;

    $('.material-select').each(function () {
        const $select = $(this);

        if (!$select.closest('.material-row').find('.selected-material-id').length) {
            const currentVal = $select.val();
            $select.html('<option value="">Select Material</option>').prop('disabled', false);

            materialsData.forEach(material => {
                const availableQty = material.remaining_qty || 0;

                if (availableQty > 0) hasAvailableMaterial = true;

                $select.append(`
                    <option 
                        value="${material.id}" 
                        data-price="${material.unit_price}" 
                        data-gst="${material.gst_rate || 0}" 
                        data-quantity="${availableQty}">
                        ${material.name} (${availableQty})
                    </option>
                `);
            });

            if (currentVal) {
                $select.val(currentVal).trigger('change');
            }
        }
    });

    if (!hasAvailableMaterial) {
        showMaterialErrorAndDisableSubmit();
    }
}

function showGlobalError(message) {
    const $box = $('#global-error-message');
    $box.text(message).removeClass('d-none');
    $('html, body').animate({ scrollTop: 0 }, 'fast');
}

function resetMaterialFields() {
    $('#materialsContainer').html(getMaterialRowHtml(0));
    materialRowIndex = 0;

    if (materialsData.length) {
        populateMaterialSelects();
    }
}

    function getMaterialRowHtml(index) {
        return `
            <div class="row material-row mb-2" data-index="${index}">
                <div class="col-md-3">
                    <label class="form-label">Material *</label>
                    <select name="items[${index}][material_id]" class="form-select material-select" required>
                        <option value="">Select Material</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price *</label>
                    <input type="number" name="items[${index}][unit_price]" class="form-control unit-price" step="0.01" readonly required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Available Qty</label>
                    <input type="number" class="form-control available-quantity" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Order Qty *</label>
                    <input type="number" name="items[${index}][quantity]" class="form-control order-quantity" min="1" step="0.01" required>
                    <div class="text-danger error-message mt-1 small" style="display: none;"></div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">GST %</label>
                    <input type="number" name="items[${index}][gst_rate]" class="form-control gst-rate" min="0" max="100" step="0.01" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Total</label>
                    <input type="number" name="items[${index}][total_price]" class="form-control total" readonly>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                </div>
            </div>`;
    }

    function getAvailableQuantity(materialId, currentRow) {
        const material = materialsData.find(m => m.id == materialId);
        if (!material) return 0;

        let totalUsed = 0;
        $('.material-row').each(function () {
            const row = $(this);
            const selectedId = row.find('.material-select').val();
            const qty = parseFloat(row.find('.order-quantity').val()) || 0;

            if (!row.is(currentRow) && selectedId == materialId) {
                totalUsed += qty;
            }
        });

        const available = parseFloat(material.quantity) || 0;
        return Math.max(0, available - totalUsed);
    }

    function updateAvailableQuantityForRow(row) {
        const materialId = row.find('.material-select').val();
        if (materialId) {
            const availQty = getAvailableQuantity(materialId, row);
            row.find('.available-quantity').val(availQty);
        }
    }

    $(document).on('change', '.material-select', function () {
        const selected = $(this).find('option:selected');
        const row = $(this).closest('.material-row');
        const materialId = $(this).val();
        const materialName = selected.text().split('(')[0].trim();
        
        row.find('.unit-price').val(selected.data('price') || '');
        row.find('.gst-rate').val(selected.data('gst') || 0);

        const availQty = getAvailableQuantity(materialId, row);
        row.find('.available-quantity').val(availQty);

        const qtyInput = row.find('.order-quantity');
        const errorBox = row.find('.error-message');
        const currentQty = parseFloat(qtyInput.val()) || 0;

        if (availQty <= 0) {
            const message = `Material '${materialName}' is out of stock. Remaining quantity is 0. You cannot create an order with out-of-stock materials.`;
            errorBox.text(message).show();
            qtyInput.prop('readonly', false).addClass('is-invalid');
            row.addClass('has-error');
            showGlobalError(message);
        } else {
            errorBox.hide();
            qtyInput.prop('readonly', false).removeClass('is-invalid');
            row.removeClass('has-error');

            if ($('.material-row.has-error').length === 0) {
                $('#global-error-message').addClass('d-none').text('');
            }
        }

        calculateRowTotal(row);
        updateOrderTotals();
    });

    $(document).on('input', '.order-quantity', function() {
        const row = $(this).closest('.material-row');
        const materialId = row.find('.material-select').val();
        const orderQty = parseFloat($(this).val()) || 0;
        const availQty = getAvailableQuantity(materialId, row);
        const errorBox = row.find('.error-message');

        row.find('.available-quantity').val(availQty);

        if (orderQty > availQty) {
            errorBox.text(`Available quantity is ${availQty}. You cannot order more.`).show();
            $(this).addClass('is-invalid');
        } else {
            errorBox.hide();
            $(this).removeClass('is-invalid');
        }

        calculateRowTotal(row);
        updateOrderTotals();
    });

    $(document).on('input', '.gst-rate', function() {
        const row = $(this).closest('.material-row');
        calculateRowTotal(row);
        updateOrderTotals();
    });

    function calculateRowTotal(row) {
        const quantity = parseFloat(row.find('.order-quantity').val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const gstRate = parseFloat(row.find('.gst-rate').val()) || 0;

        const subtotal = quantity * unitPrice;
        const gstAmount = (subtotal * gstRate) / 100;
        const total = subtotal + gstAmount;

        row.find('.total').val(total.toFixed(2));
    }

    function updateOrderTotals() {
        let totalAmount = 0, gstAmount = 0, finalAmount = 0;

        $('.material-row').each(function() {
            const quantity = parseFloat($(this).find('.order-quantity').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
            const gstRate = parseFloat($(this).find('.gst-rate').val()) || 0;

            const subtotal = quantity * unitPrice;
            const rowGst = (subtotal * gstRate) / 100;
            const rowTotal = subtotal + rowGst;

            totalAmount += subtotal;
            gstAmount += rowGst;
            finalAmount += rowTotal;
        });

        $('#total_amount').val(totalAmount.toFixed(2));
        $('#gst_amount').val(gstAmount.toFixed(2));
        $('#final_amount').val(finalAmount.toFixed(2));
    }

    $('#addMaterialRow').click(function() {
        if (materialsData.length === 0) {
            showGlobalError('Please select a vendor first.');
            return;
        }

        materialRowIndex++;
        $('#materialsContainer').append(getMaterialRowHtml(materialRowIndex));
        populateMaterialSelects();
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('.material-row').remove();
        updateRemoveButtons();
        updateOrderTotals();
    });

    function updateRemoveButtons() {
        const rows = $('.material-row');
        $('.remove-row').prop('disabled', rows.length <= 1);
    }

    $('#purchaseOrderForm').on('submit', function(e) {
        let hasValidMaterials = false;
        let hasErrors = false;
        $('#global-error-message').addClass('d-none').text('');

        $('.material-row').each(function() {
            const row = $(this);
            const materialId = row.find('.material-select').val();
            const orderQty = parseFloat(row.find('.order-quantity').val()) || 0;
            const availQty = getAvailableQuantity(materialId, row);

            if (!materialId || orderQty <= 0) {
                hasErrors = true;
                row.find('.error-message').text('Please select a material and enter quantity.').show();
                row.find('.order-quantity').addClass('is-invalid');
            }

            if (orderQty > availQty) {
                row.find('.error-message').text(`Available quantity is ${availQty}. You cannot order more.`).show();
                row.find('.order-quantity').addClass('is-invalid');
                hasErrors = true;
            }

            if (materialId && orderQty > 0 && orderQty <= availQty) {
                hasValidMaterials = true;
            }
        });

        if (hasErrors || !hasValidMaterials) {
            e.preventDefault();
            const errorMsg = !hasValidMaterials
                ? 'Please add at least one valid material with quantity.'
                : 'Please fix the material quantity errors before submitting.';
            showGlobalError(errorMsg);
        }
    });
});
</script>
@endpush