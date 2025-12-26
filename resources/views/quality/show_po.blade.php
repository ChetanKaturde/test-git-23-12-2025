@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Purchase Order: <strong>{{ $po->po_number }}</strong></h2>

    {{-- Barcode Generation Form --}}
    <form method="POST" action="{{ route('quality.generateBarcodes') }}">
        @csrf
        <input type="hidden" name="po_id" value="{{ $po->id }}">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Weight</th>
                        <th>Dimensions</th>
                        <th>Approval</th>
                        <th>Update</th>
                        <th>Select for Barcode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($po->items ?? [] as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                            <td>
                                <input form="approveForm{{ $item->id }}" type="text" name="weight" class="form-control"
                                    value="{{ $item->weight ?? ($item->material->weight_per_unit ?? '') }}">
                            </td>
                            <td>
                                <input form="approveForm{{ $item->id }}" type="text" name="dimensions"
                                    class="form-control" value="{{ $item->dimensions }}"
                                    pattern="\d+" title="Only digits allowed"
                                    oninput="this.value = this.value.replace(/\D/g, '')">
                            </td>
                            <td>
                                <select form="approveForm{{ $item->id }}" name="approval_status" class="form-select">
                                    <option value="approved" {{ $item->approval_status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $item->approval_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </td>
                            <td>
                                <form id="approveForm{{ $item->id }}" method="POST" action="{{ route('quality.approveItem', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                            <td>
                                @if($item->approval_status === 'approved')
                                    <input type="checkbox" name="approved_items[]" value="{{ $item->id }}">
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">No items found for this Purchase Order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">Generate Barcodes</button>
        </div>
    </form>

    {{-- Approve Dispatch --}}
    <form method="POST" action="{{ route('quality.approveDispatch') }}" class="mt-3">
        @csrf
        @foreach($po->items ?? [] as $item)
            @if($item->approval_status === 'approved')
                <input type="hidden" name="items[]" value="{{ $item->id }}">
            @endif
        @endforeach
        <button type="submit" class="btn btn-warning">Approve Dispatch</button>
    </form>

    {{-- Display Barcodes --}}
    @if(session('barcodes'))
    <div class="mt-5">
        <h4>Generated Barcodes</h4>
        <div class="text-end mb-3">
            <button onclick="window.print()" class="btn btn-outline-secondary">Print Barcodes</button>
        </div>
        <div class="row">
            @foreach(session('barcodes') as $entry)
                <div class="col-md-4 mb-4">
                    <div class="card p-3 text-center shadow-sm">
                        <h5 class="card-title mb-1">{{ $entry['item']->item_name }}</h5>
                        <p class="mb-1">Quantity: {{ $entry['item']->quantity }}</p>
                        <p class="mb-1 text-muted">Weight: {{ $entry['item']->weight }}</p>
                        <p class="mb-2 text-muted">Dimensions: {{ $entry['item']->dimensions }}</p>
                        <div class="barcode">{!! $entry['barcode'] !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
