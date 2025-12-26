
@extends('layouts.app')

@section('title', 'Barcode Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h3 mb-0">🎯 Barcode Details</h2>
                <div class="btn-group">
                    <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                    <a href="{{ route('barcode.batch-print', ['ids' => $barcode->id]) }}" class="btn btn-success">
                        <i class="fas fa-print me-1"></i> Print
                    </a>
                  
<a href="{{ route('barcode.edit', ['barcode' => $barcode->id]) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Barcode Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-barcode me-2"></i> Barcode Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Barcode Number</label>
                                <div class="fw-bold fs-5">{{ $barcode->barcode_number }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Material</label>
                                <div>{{ $barcode->material_name }}</div>
                                <small class="text-muted">Code: {{ $barcode->material_code }}</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Batch Number</label>
                                <div>
                                    <span class="badge bg-info">{{ $barcode->batch->batch_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                           <div class="mb-3">
    <label class="form-label text-muted">Supplier</label>
    <div>
        @if($barcode->purchaseOrder && $barcode->purchaseOrder->vendor)
            {{ $barcode->purchaseOrder->vendor->name }} - {{ $barcode->purchaseOrder->vendor->phone }}
        @else
            N/A
        @endif
    </div>
</div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Storage Location</label>
                                <div>
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $barcode->storage_location }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Quantity</label>
                                <div>{{ $barcode->quantity }} units</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Weight</label>
                                <div>{{ $barcode->weight }} kg</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Unit Price</label>
                                <div>₹ {{ number_format($barcode->unit_price, 2) }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Quality Grade</label>
                                <div>
                                    <span class="badge bg-secondary">{{ $barcode->quality_grade }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Expiry Date</label>
                                <div>
                                    @if($barcode->expiry_date)
                                        @php
                                            $isExpired = $barcode->expiry_date->isPast();
                                            $isExpiringSoon = $barcode->expiry_date->diffInDays(now()) <= 7;
                                        @endphp
                                        <span class="{{ $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : '') }}">
                                            <i class="fas fa-calendar me-1"></i>{{ $barcode->expiry_date->format('d M, Y') }}
                                        </span>
                                        @if($isExpired)
                                            <div><small class="text-danger">⚠️ Expired</small></div>
                                        @elseif($isExpiringSoon)
                                            <div><small class="text-warning">⚠️ Expiring Soon</small></div>
                                        @endif
                                    @else
                                        <span class="text-muted">No expiry date</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($barcode->notes)
                        <div class="mt-3">
                            <label class="form-label text-muted">Notes</label>
                            <div class="p-2 bg-light rounded">{{ $barcode->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-link me-2"></i> Related Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Purchase Order</h6>
                          @if($barcode->purchaseOrder)
    <div class="p-3 bg-light rounded">
        <div><strong>PO Number:</strong> {{ $barcode->purchaseOrder->po_number }}</div>

        <div>
            <strong>Order Date:</strong>
            {{ $barcode->purchaseOrder->order_date ? $barcode->purchaseOrder->order_date->format('d M, Y') : 'N/A' }}
        </div>

        <div><strong>Total Amount:</strong> ₹ {{ number_format($barcode->purchaseOrder->final_amount, 2) }}</div>

        <div class="mt-2">
            <a href="{{ route('purchase-orders.show', $barcode->purchaseOrder) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye me-1"></i> View PO
            </a>
        </div>
    </div>
@else
    <p class="text-muted">No purchase order linked</p>
@endif

                        </div>
                        <div class="col-md-6">
                            <h6>Inventory Batch</h6>
                            @if($barcode->batch)
                                <div class="p-3 bg-light rounded">
                                    <div><strong>Batch:</strong> {{ $barcode->batch->batch_number }}</div>
                                    <div><strong>Received:</strong> {{ $barcode->batch->received_date->format('d M, Y') }}</div>
                                    <div><strong>Current Stock:</strong> {{ $barcode->batch->current_quantity }} units</div>
                                    <div class="mt-2">
                                        <a href="{{ route('inventory.show', $barcode->batch) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View Batch
                                        </a>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No batch information</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barcode Visuals & Stats -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i> Status & Statistics
                    </h5>
                </div>
                <div class="card-body text-center">
                    @php
                        $statusColors = [
                            'active' => 'success',
                            'inactive' => 'secondary',
                            'damaged' => 'danger',
                            'expired' => 'warning'
                        ];
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$barcode->status] ?? 'secondary' }} fs-6 px-3 py-2">
                            {{ ucfirst($barcode->status) }}
                        </span>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 text-primary">{{ $barcode->print_count ?? 0 }}</div>
                                <small class="text-muted">Times Printed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">
                                {{ $barcode->last_scanned_at ? '✓' : '✗' }}
                            </div>
                            <small class="text-muted">Ever Scanned</small>
                        </div>
                    </div>

                    @if($barcode->last_scanned_at)
                        <div class="mt-3">
                            <small class="text-muted">
                                Last scanned: {{ $barcode->last_scanned_at->diffForHumans() }}
                            </small>
                        </div>
                    @endif

                    <div class="mt-3">
                        <small class="text-muted">
                            Created: {{ $barcode->created_at->format('d M, Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>

          <!-- Barcode Visuals -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-qrcode me-2"></i> Barcode Preview
        </h5>
    </div>
    <div class="card-body text-center">
        @if(in_array($barcode->barcode_type, ['standard', 'both']))
            <div class="mb-4">
                <h6>Standard Barcode</h6>
                <div class="p-3 bg-white border rounded">
                    <img src="{{ route('barcode.generate-barcode', ['number' => $barcode->barcode_number]) }}" 
                         alt="Barcode for {{ $barcode->barcode_number }}" 
                         class="img-fluid"
                         style="max-height: 100px;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display:none; color: red;">
                        ❌ Barcode generation failed
                    </div>
                </div>
                <div class="mt-2">
                    <small class="font-monospace fw-bold">{{ $barcode->barcode_number }}</small>
                </div>
            </div>
        @endif
        
        @if(in_array($barcode->barcode_type, ['qr', 'both']))
            <div class="mb-4">
                <h6>QR Code</h6>
                <div class="p-3 bg-white border rounded d-inline-block">
                    <img src="{{ route('barcode.generate-qr', ['data' => urlencode($barcode->qr_code_data)]) }}" 
                         alt="QR Code" 
                         class="img-fluid" 
                         style="max-width: 150px; max-height: 150px;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display:none; color: red;">
                        ❌ QR Code generation failed
                    </div>
                </div>
            </div>
        @endif
        
        <div class="mt-3">
            <a href="{{ route('barcode.batch-print', ['ids' => $barcode->id]) }}" 
               class="btn btn-success btn-sm">
                <i class="fas fa-print me-1"></i> Print Labels
            </a>
        </div>
    </div>
</div>
@endsection
