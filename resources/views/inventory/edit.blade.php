@extends('layouts.app')

@section('title', 'Edit Inventory Batch')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-edit text-primary"></i>
                Edit Inventory Batch
            </h2>
            <p class="text-muted">Modify inventory batch details</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>
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
                    <form action="{{ route('inventory.update', $batch->id) }}" method="POST" id="inventoryForm">
                        @csrf
                        @method('PATCH')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="batch_number" class="form-label">Batch Number</label>
                                <input type="text" 
                                       id="batch_number"
                                       name="batch_number"
                                       class="form-control" 
                                       value="{{ old('batch_number', $batch->batch_number) }}" 
                                       readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="purchase_order_id" class="form-label">Purchase Order</label>
                                <select id="purchase_order_id" 
                                        name="purchase_order_id" 
                                        class="form-select" disabled>
                                    <option value="">Select PO (Optional)</option>
                                    @foreach($purchaseOrders as $po)
                                        <option value="{{ $po->id }}"
                                            {{ $batch->purchase_order_id == $po->id ? 'selected' : '' }}>
                                            {{ $po->po_number }} - {{ optional($po->vendor)->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(count($selectedPoItems) > 0)
                        <div class="alert alert-info mb-4">
                            <h6><i class="fas fa-info-circle"></i> PO Item Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Item:</strong> {{ $selectedPoItems[0]->item_name }}<br>
                                    <strong>Ordered Quantity:</strong> {{ number_format($selectedPoItems[0]->quantity, 2) }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Unit Price:</strong> ₹{{ number_format($selectedPoItems[0]->unit_price, 2) }}<br>
                                    <strong>Total Value:</strong> ₹{{ number_format($selectedPoItems[0]->total_price, 2) }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="initial_quantity" class="form-label">Receiving Quantity</label>
                                <input type="number" id="initial_quantity" name="initial_quantity" 
       class="form-control {{ $remainingQty == 0 && (old('initial_quantity', $batch->initial_quantity) == 0) ? 'is-invalid' : '' }}" 
       value="{{ old('initial_quantity', $batch->initial_quantity) }}" 
       required min="0" step="0.01">

                            </div>
                            <div class="col-md-6">
                                <label for="remaining_quantity" class="form-label">Remaining Quantity</label>
                                <input type="number" id="remaining_quantity" 
                                       class="form-control" 
                                       readonly 
                                       value="{{ max(0, $orderedQty - $totalReceivedQty - $batch->initial_quantity) }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quality_grade" class="form-label">Quality Grade</label>
                                <select id="quality_grade" name="quality_grade" class="form-select">
                                    <option value="">Select Grade (Optional)</option>
                                    @foreach(['A', 'B', 'C', 'Premium', 'Standard'] as $grade)
                                        <option value="{{ $grade }}" 
                                            {{ old('quality_grade', $batch->quality_grade) == $grade ? 'selected' : '' }}>
                                            {{ $grade }}
                                        </option>
                                    @endforeach
                                </select>
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
                {{ (old('warehouse_id', $batch->warehouse_id ?? '') == $warehouse->id) ? 'selected' : '' }}>
                {{ $warehouse->name }}
            </option>
        @endforeach
    </select>
    @error('warehouse_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="received_date" class="form-label">Received Date</label>
                                <input type="date" 
                                       id="received_date"
                                       name="received_date" 
                                       class="form-control" 
                                       value="{{ old('received_date', $batch->received_date->format('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" 
                                       id="expiry_date"
                                       name="expiry_date" 
                                       class="form-control" 
                                       value="{{ old('expiry_date', optional($batch->expiry_date)->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      class="form-control" 
                                      rows="3" 
                                      maxlength="1000">{{ old('notes', $batch->notes) }}</textarea>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Batch
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><strong>Batch Summary</strong></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>PO Quantity:</span>
                        <strong>{{ number_format($orderedQty, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Already Received:</span>
                        <strong>{{ number_format($totalReceivedQty, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Receiving Now:</span>
                        <strong class="text-primary">{{ number_format($batch->initial_quantity, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Will Remain:</span>
                        <strong class="text-warning">
                            {{ number_format(max(0, $orderedQty - $totalReceivedQty - $batch->initial_quantity), 2) }}
                        </strong>
                    </div>

                    @if(count($selectedPoItems) > 0)
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Unit Price:</span>
                        <strong>₹{{ number_format($selectedPoItems[0]->unit_price, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Batch Value:</strong></span>
                        <strong class="text-success">
                            ₹{{ number_format($selectedPoItems[0]->unit_price * $batch->initial_quantity, 2) }}
                        </strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- JavaScript to ensure expiry date is not before received date --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const receivedDateInput = document.getElementById('received_date');
        const expiryDateInput = document.getElementById('expiry_date');

        function setExpiryMinDate() {
            if (receivedDateInput.value) {
                expiryDateInput.min = receivedDateInput.value;
            }
        }

        receivedDateInput.addEventListener('change', function () {
            setExpiryMinDate();

            // Optional: Clear expiry date if it’s invalid after changing received date
            if (expiryDateInput.value && expiryDateInput.value < receivedDateInput.value) {
                expiryDateInput.value = '';
            }
        });

        // Initialize on page load
        setExpiryMinDate();
    });
</script>

