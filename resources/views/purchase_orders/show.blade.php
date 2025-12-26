@extends('layouts.app')
@section('title', 'Purchase Order Details')

@section('content')
<div class="container">
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Purchase Order Details</h1>
        <div>
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

{{-- Purchase Order Details Card --}}
<div class="card mb-4">
    <div class="card-header"><h5>Order Information</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr><th>PO Number</th><td>{{ $purchaseOrder->po_number ?? 'N/A' }}</td></tr>

                {{-- Vendor Info --}}
                <tr><th>Vendor Name</th><td>{{ $purchaseOrder->vendor?->name ?? 'N/A' }}</td></tr>
                <tr><th>Business Name</th><td>{{ $purchaseOrder->vendor?->business_name ?? 'N/A' }}</td></tr>
                <tr><th>Vendor Email</th><td>{{ $purchaseOrder->vendor?->email ?? 'N/A' }}</td></tr>
                <tr><th>Vendor Phone</th><td>{{ $purchaseOrder->vendor?->phone ?? 'N/A' }}</td></tr>

                {{-- Addresses --}}
                <tr><th>Warehouse Address</th><td>{{ $purchaseOrder->vendor?->warehouse_address ?? 'N/A' }}</td></tr>
                <tr><th>Company Address</th><td>{{ $purchaseOrder->vendor?->company_address ?? 'N/A' }}</td></tr>

                {{-- Dates --}}
                <tr><th>PO Date</th><td>{{ $purchaseOrder->po_date ? \Carbon\Carbon::parse($purchaseOrder->po_date)->format('M d, Y') : 'N/A' }}</td></tr>
                <tr><th>Order Date</th><td>{{ $purchaseOrder->order_date ? \Carbon\Carbon::parse($purchaseOrder->order_date)->format('M d, Y') : 'N/A' }}</td></tr>
                <tr><th>Expected Delivery</th><td>{{ $purchaseOrder->expected_delivery ? \Carbon\Carbon::parse($purchaseOrder->expected_delivery)->format('M d, Y') : 'N/A' }}</td></tr>

                {{-- Shipping & Amounts --}}
                <tr><th>Shipping Address</th><td>{{ $purchaseOrder->shipping_address ?? 'N/A' }}</td></tr>
                <tr><th>Shipping Cost</th><td>₹{{ number_format($purchaseOrder->shipping_cost ?? 0, 2) }}</td></tr>
                <tr><th>Total Amount</th><td>₹{{ number_format($purchaseOrder->total_amount ?? 0, 2) }}</td></tr>
                <tr><th>GST Amount</th><td>₹{{ number_format($purchaseOrder->gst_amount ?? 0, 2) }}</td></tr>
                <tr><th>Final Amount</th><td><strong>₹{{ number_format($purchaseOrder->final_amount ?? 0, 2) }}</strong></td></tr>

                {{-- Status --}}
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ match($purchaseOrder->status) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'received' => 'info',
                            'cancelled' => 'danger',
                            'ordered' => 'primary',
                            'completed' => 'success',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            default => 'secondary'
                        } }}">
                            {{ ucfirst($purchaseOrder->status ?? 'Unknown') }}
                        </span>
                    </td>
                </tr>

                {{-- Notes --}}
                @if($purchaseOrder->notes)
                    <tr><th>Notes</th><td>{{ $purchaseOrder->notes }}</td></tr>
                @endif

                {{-- Timestamps --}}
                <tr><th>Created At</th><td>{{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('M d, Y h:i A') : 'N/A' }}</td></tr>
                <tr><th>Updated At</th><td>{{ $purchaseOrder->updated_at ? $purchaseOrder->updated_at->format('M d, Y h:i A') : 'N/A' }}</td></tr>
            </table>
        </div>
    </div>
</div>

{{-- Purchase Order Items --}}
@if($purchaseOrder->items && $purchaseOrder->items->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5>Order Items ({{ $purchaseOrder->items->count() }} items)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Material Code</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                            <th>GST Rate</th>
                            <th>GST Amount</th>
                            <th>Final Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $item->material?->name ?? $item->item_name ?? 'N/A' }}
                                @if($item->material?->description)
                                    <br><small class="text-muted">{{ $item->material->description }}</small>
                                @endif
                            </td>
                            <td>{{ $item->material?->code ?? 'N/A' }}</td>
                            <td>{{ number_format($item->quantity ?? 0, 2) }}</td>
                            <td>{{ $item->material?->unit ?? 'N/A' }}</td>
                            <td>₹{{ number_format($item->material?->unit_price ?? $item->unit_price ?? 0, 2) }}</td>
                            <td>₹{{ number_format($item->total_price ?? 0, 2) }}</td>
                            <td>{{ number_format($item->material?->gst_rate ?? $item->gst_rate ?? 0, 2) }}%</td>
                            <td>₹{{ number_format($item->gst_amount ?? 0, 2) }}</td>
                            <td><strong>₹{{ number_format($item->final_amount ?? $item->total_price ?? 0, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="6">Total</th>
                            <th>₹{{ number_format($purchaseOrder->total_amount ?? 0, 2) }}</th>
                            <th></th>
                            <th>₹{{ number_format($purchaseOrder->gst_amount ?? 0, 2) }}</th>
                            <th><strong>₹{{ number_format($purchaseOrder->final_amount ?? 0, 2) }}</strong></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Additional Item Summary --}}
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Order Summary</h6>
                            <p class="mb-1"><strong>Total Items:</strong> {{ $purchaseOrder->items->count() }}</p>
                            <p class="mb-1"><strong>Total Quantity:</strong> {{ number_format($purchaseOrder->items->sum('quantity'), 2) }}</p>
                            <p class="mb-0"><strong>Average Item Value:</strong> ₹{{ $purchaseOrder->items->count() > 0 ? number_format($purchaseOrder->final_amount / $purchaseOrder->items->count(), 2) : '0.00' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Amount Breakdown</h6>
                            <p class="mb-1"><strong>Subtotal:</strong> ₹{{ number_format($purchaseOrder->total_amount ?? 0, 2) }}</p>
                            <p class="mb-1"><strong>GST:</strong> ₹{{ number_format($purchaseOrder->gst_amount ?? 0, 2) }}</p>
                            <p class="mb-0"><strong>Final Total:</strong> <span class="text-success fw-bold">₹{{ number_format($purchaseOrder->final_amount ?? 0, 2) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header"><h5>Order Items</h5></div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No items found for this purchase order.
            </div>
        </div>
    </div>
@endif



    {{-- Related Work Orders --}}
    @if($purchaseOrder->status === 'received')
        <div class="card mt-4">
            <div class="card-header">
                <h5>Materials Available for Work Orders</h5>
            </div>
            <div class="card-body">
                @if($purchaseOrder->items->count() > 0)
                    <div class="row">
                        @foreach($purchaseOrder->items as $item)
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>{{ $item->material->name ?? $item->item_name }}</h6>
                                        <p class="mb-1"><strong>Available:</strong> {{ $item->quantity }} {{ $item->material->unit ?? 'pcs' }}</p>
                                        <p class="mb-0"><small class="text-muted">Ready for work orders</small></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('work-orders.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Create Work Order with These Materials
                        </a>
                    </div>
                @else
                    <p class="text-muted">No materials available.</p>
                @endif
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Order
        </a>
        @if(in_array($purchaseOrder->status, ['pending', 'cancelled']))
            <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this purchase order?');" 
                  style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        @endif
        <a href="{{ route('purchase_orders.generate_pdf', $purchaseOrder) }}" class="btn btn-info">
            <i class="fas fa-file-pdf"></i> Generate PDF
        </a>
    </div>
</div>
@endsection