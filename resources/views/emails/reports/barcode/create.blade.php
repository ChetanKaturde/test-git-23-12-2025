@extends('layouts.app')
{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li><i class="fas fa-info-circle me-1"></i> {{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@section('title', 'Generate Barcodes')

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h3 mb-0">🎯 Generate Barcodes</h2>
                <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Generate Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-qrcode me-2"></i> Barcode Generation
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('barcode.store') }}">
                        @csrf
                        
                        <!-- Batch Selection -->
                        <div class="mb-4">
                            <label class="form-label required">Select Inventory Batch</label>
                            <select name="batch_id" class="form-select @error('batch_id') is-invalid @enderror" required>
                                <option value="">Choose a batch to generate barcodes for...</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->batch_number }} - {{ $batch->material->name ?? 'Unknown Material' }}
                                        ({{ $batch->current_quantity }} units, {{ $batch->current_weight }}kg)
                                        - {{ $batch->purchaseOrder && $batch->purchaseOrder->vendor ? $batch->purchaseOrder->vendor->name : ($batch->supplier ? $batch->supplier->name : 'No Supplier') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('batch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select the inventory batch for which you want to generate barcodes.</div>
                        </div>

                        <!-- Batch Details Preview -->
                        <div id="batchDetails" class="mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Batch Details Preview</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="batchInfo"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="materialInfo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barcode Type -->
                        <div class="mb-4">
                            <label class="form-label required">Barcode Type</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="barcode_type" 
                                               id="type_standard" value="standard" 
                                               {{ old('barcode_type', 'standard') == 'standard' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_standard">
                                            <i class="fas fa-barcode me-1"></i> Standard Barcode
                                        </label>
                                        <small class="form-text text-muted">Linear barcode for basic scanning</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="barcode_type" 
                                               id="type_qr" value="qr" 
                                               {{ old('barcode_type') == 'qr' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_qr">
                                            <i class="fas fa-qrcode me-1"></i> QR Code
                                        </label>
                                        <small class="form-text text-muted">2D code with detailed information</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="barcode_type" 
                                               id="type_both" value="both" 
                                               {{ old('barcode_type') == 'both' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_both">
                                            <i class="fas fa-layer-group me-1"></i> Both Types
                                        </label>
                                        <small class="form-text text-muted">Standard barcode + QR code</small>
                                    </div>
                                </div>
                            </div>
                            @error('barcode_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Print Quantity -->
                        <div class="mb-4">
                            <label class="form-label required">Number of Barcodes to Generate</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" name="print_quantity" 
                                           class="form-control @error('print_quantity') is-invalid @enderror" 
                                           value="{{ old('print_quantity', 1) }}" min="1" max="100" required>
                                    @error('print_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Generate multiple barcodes for the same batch (Max: 100)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Optional notes about this barcode generation...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-qrcode me-1"></i> Generate Barcodes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Batch details data
    const batchData = @json($batches->keyBy('id'));
    
    // Handle batch selection change
    $('select[name="batch_id"]').change(function() {
        const batchId = $(this).val();
        
        if (batchId && batchData[batchId]) {
            const batch = batchData[batchId];
            showBatchDetails(batch);
        } else {
            $('#batchDetails').hide();
        }
    });

    function showBatchDetails(batch) {
        // Get supplier name
        let supplierName = 'N/A';
        if (batch.purchase_order && batch.purchase_order.vendor) {
            supplierName = batch.purchase_order.vendor.name;
        } else if (batch.supplier) {
            supplierName = batch.supplier.name;
        }

        const batchInfo = `
            <div class="mb-2">
                <strong>Batch Number:</strong><br>
                <span class="badge bg-info">${batch.batch_number}</span>
            </div>
            <div class="mb-2">
                <strong>Supplier:</strong><br>
                ${supplierName}
            </div>
            <div class="mb-2">
                <strong>Location:</strong><br>
                <i class="fas fa-map-marker-alt me-1"></i>${batch.storage_location || 'N/A'}
            </div>
        `;

        const materialInfo = `
            <div class="mb-2">
                <strong>Material:</strong><br>
                ${batch.material ? batch.material.name : 'Unknown'} (${batch.material ? batch.material.code : 'N/A'})
            </div>
            <div class="mb-2">
                <strong>Current Stock:</strong><br>
                <i class="fas fa-boxes me-1"></i>${batch.current_quantity} units, ${batch.current_weight}kg
            </div>
            <div class="mb-2">
                <strong>Quality Grade:</strong><br>
                <span class="badge bg-secondary">${batch.quality_grade}</span>
            </div>
            <div class="mb-2">
                <strong>Expiry Date:</strong><br>
                <i class="fas fa-calendar me-1"></i>${batch.expiry_date ? new Date(batch.expiry_date).toLocaleDateString() : 'No expiry'}
            </div>
        `;

        $('#batchInfo').html(batchInfo);
        $('#materialInfo').html(materialInfo);
        $('#batchDetails').show();
    }

    // Show batch details if batch is pre-selected
    if ($('select[name="batch_id"]').val()) {
        $('select[name="batch_id"]').trigger('change');
    }
});
</script>
@endpush
@endsection
