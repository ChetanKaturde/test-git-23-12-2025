@extends('layouts.app')

@section('title', 'Add New Inventory Batch')

@section('content')
<!-- Add CSRF token meta tag for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-plus-circle text-primary"></i>
                Add New Inventory Batch
            </h2>
            <p class="text-muted">Create a new inventory batch for material management</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>
    </div>

    <!-- Warning Alert -->
    <div id="po-warning" class="alert alert-warning" style="display: none; color:red;font-size:18px;">
        <i class="fas fa-exclamation-triangle"></i>
        <span id="warning-message"></span>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Batch Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.store') }}" method="POST" id="inventoryForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="batch_number" class="form-label">Batch Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="batch_number"
                                           name="batch_number" 
                                           class="form-control @error('batch_number') is-invalid @enderror" 
                                           value="{{ old('batch_number', $suggestedBatchNumber ?? '') }}" 
                                           readonly>
                                    <button type="button" class="btn btn-outline-primary" id="generateBatchBtn">
                                        <i class="fas fa-sync-alt"></i> Generate
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="editBatchBtn" title="Edit manually">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                @error('batch_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Auto-generated unique identifier. Click 'Generate' for new number or 'Edit' to modify manually.</small>
                            </div>
                        </div>
                        
       <!-- PO Selection -->
<div class="row mb-4">
    <div class="col-md-12">
        <label for="purchase_order_id" class="form-label">Purchase Order</label>
        <select id="purchase_order_id" 
                name="purchase_order_id" 
                class="form-select @error('purchase_order_id') is-invalid @enderror"
                onchange="location = '?purchase_order_id=' + this.value;">
            <option value="">Select PO (Optional)</option>
            @foreach($purchaseOrders as $po)
                <option value="{{ $po->id }}"
                    {{ request('purchase_order_id') == $po->id ? 'selected' : '' }}>
                    {{ $po->po_number }} - {{ optional($po->vendor)->name ?? 'N/A' }} - {{ optional($po->vendor)->business_name ?? 'N/A' }}
                </option>
            @endforeach
        </select>
        @error('purchase_order_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@if(count($selectedPoItems) > 0)
<div class="alert alert-info mb-4">
    <h6><i class="fas fa-info-circle"></i> PO Item Information</h6>

    <div class="form-group mb-3">
        <label for="material_id"><strong>Select Material / Item:</strong></label>
        <select name="material_id" id="material_id" class="form-select" required>
            <option value="">-- Select Material --</option>
            @foreach ($selectedPoItems as $item)
                <option value="{{ $item->material_id }}"
                    data-item-name="{{ $item->item_name }}"
                    data-quantity="{{ $item->quantity }}"
                    data-unit-price="{{ $item->unit_price }}"
                    data-total-price="{{ $item->total_price }}">
                    {{ $item->item_name }} (Qty: {{ number_format($item->quantity, 2) }}, ₹{{ number_format($item->unit_price, 2) }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Summary Info Display -->
    <div class="row">
        <div class="col-md-6">
            <strong>Item:</strong> <span id="po-item-name">-</span><br>
            <strong>Ordered Quantity:</strong> <span id="po-item-qty">-</span><br>
        </div>
        <div class="col-md-6">
            <strong>Unit Price:</strong> ₹<span id="po-item-price">-</span><br>
            <strong>Total Value:</strong> ₹<span id="po-item-total">-</span>
        </div>
    </div>
</div>
@endif


                        <!-- Initial Quantity and Remaining Quantity -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="initial_quantity" class="form-label">Receiving Quantity <span class="text-danger">*</span></label>
                                <input type="number" 
                                       id="initial_quantity" 
                                       name="initial_quantity" 
                                       class="form-control @error('initial_quantity') is-invalid @enderror" 
                                       value="{{ old('initial_quantity') }}" 
                                       required 
                                       min="0" 
                                       step="0.01">
                                @error('initial_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Quantity receiving in this batch</small>
                            </div>

                            <div class="col-md-6">
                                <label for="remaining_quantity" class="form-label">Remaining Quantity</label>
                                <input type="number" 
                                       id="remaining_quantity" 
                                       class="form-control" 
                                       readonly 
                                       value="{{ old('remaining_quantity') }}">
                                <small class="form-text text-muted">Quantity remaining to be received</small>
                            </div>
                        </div>
                              
                        <!-- Quality and Storage -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quality_grade" class="form-label">Quality Grade</label>
                                <select id="quality_grade" 
                                        name="quality_grade" 
                                        class="form-select @error('quality_grade') is-invalid @enderror">
                                    <option value="">Select Grade (Optional)</option>
                                    <option value="A" {{ old('quality_grade') == 'A' ? 'selected' : '' }}>Grade A</option>
                                    <option value="B" {{ old('quality_grade') == 'B' ? 'selected' : '' }}>Grade B</option>
                                    <option value="C" {{ old('quality_grade') == 'C' ? 'selected' : '' }}>Grade C</option>
                                    <option value="Premium" {{ old('quality_grade') == 'Premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="Standard" {{ old('quality_grade') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                </select>
                                @error('quality_grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                          
                          <!-- Warehouse Selection -->
<div class="col-md-6">
    <label for="warehouse_id" class="form-label">Warehouse</label>
    <select id="warehouse_id"
            name="warehouse_id"
            class="form-select @error('warehouse_id') is-invalid @enderror">
        <option value="">Select Warehouse</option>
        @foreach ($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}"
                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                {{ $warehouse->name }}
            </option>
        @endforeach
    </select>
    @error('warehouse_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        </div>

                        <!-- Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="received_date" class="form-label">Received Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       id="received_date"
                                       name="received_date" 
                                       class="form-control @error('received_date') is-invalid @enderror" 
                                       value="{{ old('received_date', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required>
                                @error('received_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" 
                                       id="expiry_date"
                                       name="expiry_date" 
                                       class="form-control @error('expiry_date') is-invalid @enderror" 
                                       value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave blank if no expiry date</small>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea id="notes"
                                      name="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" 
                                      maxlength="1000" 
                                      placeholder="Additional notes about this batch...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum 1000 characters</small>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Batch
                            </button>
                            <button type="submit" name="save_and_new" value="1" class="btn btn-success">
                                <i class="fas fa-plus"></i> Save & Add Another
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Summary Section -->
            <div class="card mb-4">
                <div class="card-header"><strong>Batch Summary</strong></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>PO Quantity:</span>
                        <strong id="summary-po-quantity">-</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Already Received:</span>
                        <strong id="summary-current-quantity">-</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Receiving Now:</span>
                        <strong id="summary-receiving-quantity" class="text-primary">-</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Will Remain:</span>
                        <strong id="summary-remaining-quantity" class="text-warning">-</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Unit Price:</span>
                        <strong id="summary-price">₹-</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span><strong>Batch Value:</strong></span>
                        <strong class="text-success" id="summary-total">₹-</strong>
                    </div>
                </div>
            </div>
                
            <!-- Guidelines Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb text-warning"></i> Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Ensure batch number is unique
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Verify material and PO selection
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Check receiving quantity doesn't exceed remaining
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Set appropriate storage location
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success"></i>
                            Check expiry date if applicable
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const materialSelect = document.getElementById('material_id');
    const poSelect = document.getElementById('purchase_order_id');
    const remainingQtyInput = document.getElementById('remaining_quantity');
    const receivingQtyInput = document.getElementById('initial_quantity');
    const warningBox = document.getElementById('po-warning');
    const warningMessage = document.getElementById('warning-message');
    const batchNumberInput = document.getElementById('batch_number');
    const generateBatchBtn = document.getElementById('generateBatchBtn');
    const editBatchBtn = document.getElementById('editBatchBtn');
    const inventoryForm = document.getElementById('inventoryForm');

    // Summary Elements
    const summaryPoQty = document.getElementById('summary-po-quantity');
    const summaryCurrentQty = document.getElementById('summary-current-quantity');
    const summaryReceivingQty = document.getElementById('summary-receiving-quantity');
    const summaryRemainingQty = document.getElementById('summary-remaining-quantity');
    const summaryPrice = document.getElementById('summary-price');
    const summaryTotal = document.getElementById('summary-total');

    let orderedQty = 0;
    let alreadyReceivedQty = 0;
    let unitPrice = 0;

    // Display Material Details
    if (materialSelect) {
        materialSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const itemName = selectedOption.getAttribute('data-item-name');
            const qty = selectedOption.getAttribute('data-quantity');
            const price = selectedOption.getAttribute('data-unit-price');
            const total = selectedOption.getAttribute('data-total-price');

            document.getElementById('po-item-name').textContent = itemName || '-';
            document.getElementById('po-item-qty').textContent = parseFloat(qty || 0).toFixed(2);
            document.getElementById('po-item-price').textContent = parseFloat(price || 0).toFixed(2);
            document.getElementById('po-item-total').textContent = parseFloat(total || 0).toFixed(2);
        });
    }

    // Show warning
    function showWarning(message) {
        if (warningBox && warningMessage) {
            warningMessage.textContent = message;
            warningBox.style.display = 'block';
        }
    }

    function clearWarning() {
        warningBox.style.display = 'none';
        warningMessage.textContent = '';
    }

    function updateSummary() {
        const receivingQty = parseFloat(receivingQtyInput.value) || 0;
        const remainingAfterReceiving = Math.max(0, orderedQty - alreadyReceivedQty - receivingQty);
        const totalValue = receivingQty * unitPrice;

        summaryPoQty.textContent = orderedQty.toFixed(2);
        summaryCurrentQty.textContent = alreadyReceivedQty.toFixed(2);
        summaryReceivingQty.textContent = receivingQty.toFixed(2);
        summaryRemainingQty.textContent = remainingAfterReceiving.toFixed(2);
        summaryPrice.textContent = '₹' + unitPrice.toFixed(2);
        summaryTotal.textContent = '₹' + totalValue.toFixed(2);
    }

    function clearSummary() {
        summaryPoQty.textContent = '-';
        summaryCurrentQty.textContent = '-';
        summaryReceivingQty.textContent = '-';
        summaryRemainingQty.textContent = '-';
        summaryPrice.textContent = '₹-';
        summaryTotal.textContent = '₹-';
    }

    function fetchRemainingQty(poId) {
        if (!poId) {
            clearWarning();
            clearSummary();
            remainingQtyInput.value = '';
            receivingQtyInput.disabled = false;
            return;
        }

        const materialId = materialSelect?.value || '';
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(`/get-po-remaining-quantity?purchase_order_id=${poId}&material_id=${materialId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            return res.json();
        })
        .then(data => {
            if (data.error) throw new Error(data.error);

            orderedQty = parseFloat(data.ordered_quantity || 0);
            alreadyReceivedQty = parseFloat(data.received_quantity || 0);
            unitPrice = parseFloat(data.unit_price || 0);
            const remainingQty = parseFloat(data.remaining_quantity || 0);

            remainingQtyInput.value = remainingQty.toFixed(2);

            if (remainingQty <= 0) {
                showWarning("Cannot create batch. This PO item is fully received.");
                receivingQtyInput.disabled = true;
                receivingQtyInput.value = '';
            } else {
                clearWarning();
                receivingQtyInput.disabled = false;
            }

            updateSummary();
        })
        .catch(err => {
            console.error("Error fetching PO data:", err);
            showWarning("Could not load PO quantity: " + err.message);
            remainingQtyInput.value = '0.00';
            receivingQtyInput.disabled = true;
            clearSummary();
        });
    }

    // Initial fetch if PO is preselected
    if (poSelect.value) {
        fetchRemainingQty(poSelect.value);
    }

    // On PO change
    poSelect.addEventListener('change', function () {
        fetchRemainingQty(this.value);
    });

    // On Receiving Quantity Input
    receivingQtyInput.addEventListener('input', function () {
        const receivingQty = parseFloat(this.value) || 0;
        const remaining = orderedQty - alreadyReceivedQty;

        if (poSelect.value && remaining <= 0) {
            showWarning("Cannot create batch. Remaining quantity is 0.");
            this.value = '';
            remainingQtyInput.value = '0.00';
            updateSummary();
            return;
        }

        if (receivingQty > remaining) {
            showWarning("Receiving quantity cannot exceed remaining quantity.");
            this.value = remaining.toFixed(2);
            remainingQtyInput.value = '0.00';
        } else {
            clearWarning();
            const updatedRemaining = remaining - receivingQty;
            remainingQtyInput.value = Math.max(0, updatedRemaining).toFixed(2);
        }

        updateSummary();
    });

    // Batch Number Generation
    if (generateBatchBtn) {
        generateBatchBtn.addEventListener('click', function () {
            const timestamp = Date.now();
            const randomNum = Math.floor(Math.random() * 1000);
            batchNumberInput.value = `BATCH-${timestamp}-${randomNum}`;
        });
    }

    // Batch Number Edit Lock Toggle
    if (editBatchBtn) {
        editBatchBtn.addEventListener('click', function () {
            const isReadOnly = batchNumberInput.readOnly;
            batchNumberInput.readOnly = !isReadOnly;
            batchNumberInput.focus();
            this.innerHTML = isReadOnly ? '<i class="fas fa-lock"></i>' : '<i class="fas fa-edit"></i>';
            this.title = isReadOnly ? 'Lock batch number' : 'Edit manually';
        });
    }

    // Form Submission Validation
    if (inventoryForm) {
        inventoryForm.addEventListener('submit', function (e) {
            const receivingQty = parseFloat(receivingQtyInput.value) || 0;
            const remaining = orderedQty - alreadyReceivedQty;

            if (poSelect.value && receivingQty > remaining) {
                e.preventDefault();
                showWarning("Cannot submit: Receiving quantity exceeds remaining quantity.");
            }

            if (poSelect.value && remaining <= 0) {
                e.preventDefault();
                showWarning("Cannot submit: No remaining quantity to receive.");
            }
        });
    }
});
</script>


@endsection