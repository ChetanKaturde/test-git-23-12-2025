@extends('layouts.app')

@section('title', 'Inventory Batch Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-box-open text-primary"></i>
                Inventory Batch Details
            </h2>
            <p class="text-muted">Details for batch <strong>{{ $batch->batch_number }}</strong></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Inventory List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Batch Details Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Batch Information
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Batch Number</dt>
                        <dd class="col-sm-8">{{ $batch->batch_number }}</dd>

                      <!--  <dt class="col-sm-4">Supplier Batch Number</dt>
                        <dd class="col-sm-8">{{ $batch->supplier_batch_number ?? '-' }}</dd> -->

                        <dt class="col-sm-4">Material</dt>
                        <dd class="col-sm-8">{{ $batch->material->name ?? 'N/A' }} ({{ $batch->material->code ?? '-' }})</dd>

                        <dt class="col-sm-4">Purchase Order</dt>
                        <dd class="col-sm-8">
                            @if($batch->purchaseOrder)
                                {{ $batch->purchaseOrder->po_number }} - {{ $batch->purchaseOrder->vendor->name ?? 'N/A' }} - {{ $batch->purchaseOrder->vendor->business_name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </dd>

                        <dt class="col-sm-4">Received Quantity</dt>
                        <dd class="col-sm-8">{{ number_format($batch->received_quantity) }}</dd>

                        <dt class="col-sm-4">Current Quantity</dt>
                        <dd class="col-sm-8">{{ number_format($batch->current_quantity) }}</dd>

                        <dt class="col-sm-4">Unit Price (₹)</dt>
                        <dd class="col-sm-8">₹ {{ number_format($batch->unit_price, 2) }}</dd>

                        <dt class="col-sm-4">Warehouse Location</dt>
                        <dd class="col-sm-8">{{ $batch->warehouse->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Expiry Date</dt>
                        <dd class="col-sm-8">{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d M, Y') : '-' }}</dd>

                        <dt class="col-sm-4">Quality Grade</dt>
                        <dd class="col-sm-8">{{ $batch->quality_grade ?? '-' }}</dd>

                        <dt class="col-sm-4">Received Date</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($batch->received_date)->format('d M, Y') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge 
                                @switch($batch->status)
                                    @case('active') bg-success @break
                                    @case('expired') bg-danger @break
                                    @case('damaged') bg-warning text-dark @break
                                    @case('exhausted') bg-secondary @break
                                    @default bg-info
                                @endswitch
                            ">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Notes</dt>
                        <dd class="col-sm-8">{!! nl2br(e($batch->notes)) ?: '-' !!}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator"></i> Value Summary
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalValue = $batch->received_quantity * $batch->unit_price;
                    @endphp
                    <p><strong>Initial Quantity:</strong> {{ number_format($batch->received_quantity) }}</p>
                    <p><strong>Unit Price:</strong> ₹ {{ number_format($batch->unit_price, 2) }}</p>
                    <hr>
                    <p class="h5 text-primary">Total Value: ₹ {{ number_format($totalValue, 2) }}</p>
                </div>
            </div>

            <!-- Optional Actions -->
            <div class="card shadow-sm mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-tools"></i> Actions
                    </h6>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <a href="{{ route('inventory.edit', $batch->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Batch
                    </a>
                    <form method="POST" action="{{ route('inventory.destroy', $batch->id) }}" onsubmit="return confirm('Are you sure you want to delete this batch?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete Batch
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
