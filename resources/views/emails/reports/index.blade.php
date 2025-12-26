@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Inventory Reports</h2>
    
    {{-- Filter Form --}}
    <form method="GET" action="{{ route('reports.index') }}">
        <div class="row g-3">
            <div class="col-md-2 col-sm-6">
                <label for="from_date" class="form-label">From Date:</label>
                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <label for="to_date" class="form-label">To Date:</label>
                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="material_id" class="form-label">Material:</label>
                <select id="material_id" name="material_id" class="form-select">
                    <option value="">All Materials</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                            {{ $material->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label for="type" class="form-label">Type:</label>
                <select id="type" name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="intake" {{ request('type') == 'intake' ? 'selected' : '' }}>Intake</option>
                    <option value="dispatch" {{ request('type') == 'dispatch' ? 'selected' : '' }}>Dispatch</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            <div class="col-md-1 col-sm-6">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>

    <hr class="my-4">

    {{-- Export buttons --}}
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="{{ route('reports.exportPDF', request()->query()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        @if(method_exists('App\Http\Controllers\ReportController', 'exportExcel'))
        <a href="{{ route('reports.exportExcel', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        @endif
       
    </div>

    {{-- Results Summary --}}
    @if($transactions->total() > 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} results
        </div>
    @endif

    {{-- Table or Empty State --}}
    @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Material</th>
                        <th>Type</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Weight (kg)</th>
                        <th class="text-end">Value (₹)</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $txn)
                        <tr>
                            <td>{{ $txn->transaction_id }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $txn->batch->material->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $txn->type == 'intake' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($txn->type) }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($txn->quantity) }}</td>
                            <td class="text-end">{{ number_format($txn->weight, 2) }}</td>
                            <td class="text-end">₹{{ number_format($txn->total_value ?? 0, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($txn->transaction_date)->format('d M Y') }}</td>
                            <td>
                                @if(Route::has('transactions.show'))
                                <a href="{{ route('transactions.show', $txn->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                @if($transactions->count() > 5)
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3">Page Total</th>
                        <th class="text-end">{{ number_format($transactions->sum('quantity')) }}</th>
                        <th class="text-end">{{ number_format($transactions->sum('weight'), 2) }}</th>
                        <th class="text-end">₹{{ number_format($transactions->sum('total_value'), 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <h5>No transactions found</h5>
            <p class="mb-0">No transactions match your selected criteria. Try adjusting your filters.</p>
        </div>
    @endif
</div>

{{-- Print Styles --}}
<style>
@media print {
    .btn, .pagination, .alert-info {
        display: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>
@endsection