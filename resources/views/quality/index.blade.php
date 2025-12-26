@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Select Purchase Order</h2>

    @if($purchaseOrders->isEmpty())
        <div class="alert alert-warning">
            No purchase orders available.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($purchaseOrders as $po)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">PO Number: {{ $po->po_number }}</h5>
                            <p class="card-text">Vendor ID: {{ $po->vendor_id }}</p>
                            <a href="{{ route('quality.showPO', $po->id) }}" class="btn btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
