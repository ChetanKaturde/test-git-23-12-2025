@extends('layouts.app')

@section('title', 'Barcode Dashboard')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">📊 Barcode Dashboard</h1>
                    <p class="text-muted mb-0">Monitor your inventory barcode system</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('barcode.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Generate Barcodes
                    </a>
                    <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stats-icon bg-primary bg-gradient rounded-circle mx-auto mb-3">
                        <i class="fas fa-barcode text-white"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ number_format($stats['total_barcodes']) }}</h3>
                    <p class="stats-label text-muted mb-0">Total Barcodes</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stats-icon bg-success bg-gradient rounded-circle mx-auto mb-3">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ number_format($stats['active_barcodes']) }}</h3>
                    <p class="stats-label text-muted mb-0">Active Barcodes</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stats-icon bg-danger bg-gradient rounded-circle mx-auto mb-3">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ number_format($stats['expired_items']) }}</h3>
                    <p class="stats-label text-muted mb-0">Expired Items</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stats-icon bg-warning bg-gradient rounded-circle mx-auto mb-3">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ number_format($stats['expiring_soon']) }}</h3>
                    <p class="stats-label text-muted mb-0">Expiring Soon</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stats-icon bg-info bg-gradient rounded-circle mx-auto mb-3">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ number_format($stats['total_scans']) }}</h3>
                    <p class="stats-label text-muted mb-0">Total Scans</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stats-icon bg-secondary bg-gradient rounded-circle mx-auto mb-3">
                        <i class="fas fa-print text-white"></i>
                    </div>
                    <h3 class="stats-number mb-1">{{ number_format($stats['printed_today']) }}</h3>
                    <p class="stats-label text-muted mb-0">Printed Today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Cards -->
    <div class="row g-4">
        
        <!-- Recent Barcodes -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Recent Barcodes
                        </h5>
                        <a href="{{ route('barcode.index') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentBarcodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Material</th>
                                        <th>Batch</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentBarcodes as $barcode)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="barcode-icon me-2">
                                                        <i class="fas fa-barcode text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $barcode->barcode_number }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-medium">{{ $barcode->material_name ?? 'No material' }}</div>
                                                    @if($barcode->material_code)
                                                        <small class="text-muted">{{ $barcode->material_code }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $barcode->batch->batch_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match($barcode->status) {
                                                        'active' => 'bg-success',
                                                        'inactive' => 'bg-secondary',
                                                        'damaged' => 'bg-danger',
                                                        'expired' => 'bg-warning',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($barcode->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $barcode->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('barcode.show', $barcode) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('barcode.batch-print', ['ids' => $barcode->id]) }}" 
                                                       class="btn btn-outline-secondary btn-sm" 
                                                       title="Print">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-barcode text-muted mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">No recent barcodes found</h6>
                            <p class="text-muted mb-0">Start by generating your first barcode</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expiring Items -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Expiring Items
                        </h5>
                        @if($stats['expiring_soon'] > 0)
                            <span class="badge bg-warning text-dark">{{ $stats['expiring_soon'] }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($expiringItems->count() > 0)
                        <div class="expiring-items">
                            @foreach ($expiringItems as $barcode)
                                <div class="expiring-item p-3 mb-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $barcode->barcode_number }}</h6>
                                            <p class="mb-1 text-muted small">{{ $barcode->material_name ?? 'No material' }}</p>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt text-warning me-1"></i>
                                                <small class="text-danger fw-medium">
                                                    Exp: {{ $barcode->expiry_date->format('d M, Y') }}
                                                </small>
                                            </div>
                                            @php
                                                $daysLeft = now()->diffInDays($barcode->expiry_date, false);
                                            @endphp
                                            @if($daysLeft >= 0)
                                                <small class="text-muted">
                                                    ({{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left)
                                                </small>
                                            @else
                                                <small class="text-danger">
                                                    (Expired {{ abs($daysLeft) }} {{ Str::plural('day', abs($daysLeft)) }} ago)
                                                </small>
                                            @endif
                                        </div>
                                        <div class="ms-2">
                                            <a href="{{ route('barcode.show', $barcode) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">No items expiring soon</h6>
                            <p class="text-muted mb-0">All items are within safe expiry dates</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('barcode.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-plus-circle mb-2 d-block" style="font-size: 1.5rem;"></i>
                                Generate New Barcodes
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('barcode.index', ['status' => 'active']) }}" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-list mb-2 d-block" style="font-size: 1.5rem;"></i>
                                View Active Barcodes
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('barcode.index', ['expiry_filter' => 'expiring_soon']) }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-clock mb-2 d-block" style="font-size: 1.5rem;"></i>
                                Check Expiring Items
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button onclick="openScanModal()" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-qrcode mb-2 d-block" style="font-size: 1.5rem;"></i>
                                Scan Barcode
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scan Modal -->
<div class="modal fade" id="scanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode me-2"></i>Scan Barcode
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="scanInput" class="form-label">Enter or scan barcode number:</label>
                    <input type="text" class="form-control form-control-lg" id="scanInput" 
                           placeholder="Scan or type barcode number..." autofocus>
                </div>
                <div id="scanResult" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="performScan()">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.stats-label {
    font-size: 0.875rem;
    font-weight: 500;
}

.expiring-item {
    border-left: 4px solid #ffc107;
    transition: all 0.2s ease-in-out;
}

.expiring-item:hover {
    background-color: #fff3cd !important;
    border-left-color: #ff8c00;
}

.barcode-icon {
    width: 24px;
    text-align: center;
}

.card {
    border-radius: 12px;
}

.btn {
    border-radius: 8px;
}

.table th {
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .stats-number {
        font-size: 1.5rem;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function openScanModal() {
    const modal = new bootstrap.Modal(document.getElementById('scanModal'));
    modal.show();
    
    // Clear previous results
    document.getElementById('scanResult').innerHTML = '';
    document.getElementById('scanInput').value = '';
    
    // Focus on input after modal is shown
    setTimeout(() => {
        document.getElementById('scanInput').focus();
    }, 300);
}

function performScan() {
    const barcodeNumber = document.getElementById('scanInput').value.trim();
    const resultDiv = document.getElementById('scanResult');
    
    if (!barcodeNumber) {
        resultDiv.innerHTML = '<div class="alert alert-warning">Please enter a barcode number</div>';
        return;
    }
    
    // Show loading
    resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
    
    // Make AJAX request to scan endpoint
    fetch('{{ route("barcode.scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            barcode_number: barcodeNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = data.data;
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6 class="mb-2"><i class="fas fa-check-circle me-1"></i>Barcode Found!</h6>
                    <div class="row g-2">
                        <div class="col-6"><strong>Material:</strong></div>
                        <div class="col-6">${item.material || 'N/A'}</div>
                        <div class="col-6"><strong>Code:</strong></div>
                        <div class="col-6">${item.material_code || 'N/A'}</div>
                        <div class="col-6"><strong>Batch:</strong></div>
                        <div class="col-6">${item.batch_number || 'N/A'}</div>
                        <div class="col-6"><strong>Supplier:</strong></div>
                        <div class="col-6">${item.supplier || 'N/A'}</div>
                        <div class="col-6"><strong>Status:</strong></div>
                        <div class="col-6"><span class="badge bg-success">${item.status}</span></div>
                        ${item.expiry_date ? `
                        <div class="col-6"><strong>Expiry:</strong></div>
                        <div class="col-6">${item.expiry_date}</div>
                        ` : ''}
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-times-circle me-1"></i>${data.message}</div>`;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-1"></i>Error occurred while scanning</div>';
        console.error('Scan error:', error);
    });
}

// Allow Enter key to trigger scan
document.getElementById('scanInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performScan();
    }
});

// Auto-refresh stats every 5 minutes
setInterval(() => {
    window.location.reload();
}, 300000);
</script>
@endpush