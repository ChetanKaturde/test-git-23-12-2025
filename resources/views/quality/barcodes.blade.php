@extends('layouts.app')

@push('styles')
<style>
    @media print {
        .navbar, .btn, .alert, footer {
            display: none !important;
        }
        .card {
            page-break-inside: avoid;
            border: none;
            box-shadow: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Generated Barcodes</h2>

    @if(empty($barcodes))
        <div class="alert alert-warning">
            No barcodes generated.
        </div>
    @else
        <div class="text-end mb-3">
            <button onclick="window.print()" class="btn btn-outline-secondary">Print</button>
        </div>

        <div class="row">
        @foreach($barcodes as $entry)
    <div class="col-md-4 mb-4">
        <div class="card p-3 text-center shadow-sm">
            <h5 class="card-title mb-1">
                {{ $entry['item']->item_name }}
            </h5>
            <p class="mb-1 fw-semibold text-primary">
                SKU: {{ $entry['item']->sku }}
            </p>
            <p class="mb-1">
                Quantity: {{ $entry['item']->quantity }}
            </p>
            <p class="mb-1 text-muted">
                Weight: {{ $entry['item']->weight }}
            </p>
            <p class="mb-2 text-muted">
                Dimensions: {{ $entry['item']->dimensions }}
            </p>
            <div class="barcode">
                {!! $entry['barcode'] !!}
            </div>
        </div>
    </div>
@endforeach

        </div>
    @endif
</div>
@endsection
