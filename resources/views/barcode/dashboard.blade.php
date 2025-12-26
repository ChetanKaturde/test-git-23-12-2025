@extends('layouts.app')

@section('title', 'Barcode Dashboard')

@section('content')

   <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- CSRF Token for Laravel -->
    <meta name="csrf-token" content="your-csrf-token-here">

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
                <!-- Scan Barcode Button -->
                <button id="scanBarcodeBtn" class="btn btn-outline-info w-100 py-3 scan-btn">
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
    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanModalLabel">
                        <i class="fas fa-qrcode me-2"></i>Scan Barcode
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scanInput" class="form-label">Enter or scan barcode number:</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg" id="scanInput" 
                                   placeholder="Scan or type barcode number..." 
                                   autocomplete="off" 
                                   autofocus>
                            <button class="btn btn-outline-secondary" type="button" onclick="clearScanInput()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            You can scan using a barcode scanner or type manually
                        </div>
                    </div>
                    <div id="scanResult" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="performScan()" id="scanButton">
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

<!-- Bootstrap JS Bundle (includes Popper) -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
@push('scripts')
<script>
// Fixed JavaScript for Barcode Scanning
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing scan barcode functionality...');
    
    // Attach click handler to scan button
    const scanBarcodeBtn = document.getElementById('scanBarcodeBtn');
    if (scanBarcodeBtn) {
        console.log('Scan button found, attaching event listener...');
        scanBarcodeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openScanModal();
        });
    } else {
        console.error('Scan button not found!');
    }

    // Allow Enter key to trigger scan
    const scanInput = document.getElementById('scanInput');
    if (scanInput) {
        scanInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performScan();
            }
        });

        // Auto-clear result when input changes
        scanInput.addEventListener('input', function() {
            const resultDiv = document.getElementById('scanResult');
            if (resultDiv && (resultDiv.innerHTML.includes('alert-danger') || resultDiv.innerHTML.includes('alert-warning'))) {
                resultDiv.innerHTML = '';
            }
        });
    }

    // Reset modal when closed
    const scanModal = document.getElementById('scanModal');
    if (scanModal) {
        scanModal.addEventListener('hidden.bs.modal', function() {
            clearScanInput();
        });
    }
});

function openScanModal() {
    console.log('Opening scan modal...');
    
    const modalElement = document.getElementById('scanModal');
    if (!modalElement) {
        console.error('Modal element not found!');
        return;
    }

    // Initialize Bootstrap modal
    let modal;
    try {
        modal = new bootstrap.Modal(modalElement);
    } catch (error) {
        console.error('Bootstrap modal initialization failed:', error);
        return;
    }

    // Clear previous input and result
    clearScanInput();
    const scanResult = document.getElementById('scanResult');
    if (scanResult) {
        scanResult.innerHTML = '';
    }

    // Show the modal
    modal.show();

    // Focus on the input after the modal is shown
    modalElement.addEventListener('shown.bs.modal', function() {
        const scanInput = document.getElementById('scanInput');
        if (scanInput) {
            scanInput.focus();
        }
    }, { once: true });
}

function clearScanInput() {
    const scanInput = document.getElementById('scanInput');
    const scanResult = document.getElementById('scanResult');
    if (scanInput) scanInput.value = '';
    if (scanResult) scanResult.innerHTML = '';
}

function performScan() {
    console.log('Performing scan...');
    
    const barcodeInput = document.getElementById('scanInput');
    const resultDiv = document.getElementById('scanResult');
    const scanButton = document.getElementById('scanButton');
    
    if (!barcodeInput || !resultDiv) {
        console.error('Required elements not found!');
        return;
    }
    
    const barcodeNumber = barcodeInput.value.trim();
    
    // Validation
    if (!barcodeNumber) {
        resultDiv.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>Please enter a barcode number</div>';
        barcodeInput.focus();
        return;
    }
    
    // Disable button and show loading
    if (scanButton) {
        scanButton.disabled = true;
        scanButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Searching...';
    }
    
    resultDiv.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin me-2"></i>Searching barcode database...</div>';
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
    
    if (!csrfToken) {
        console.error('CSRF token not found!');
        resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Security token not found. Please refresh the page.</div>';
        if (scanButton) {
            scanButton.disabled = false;
            scanButton.innerHTML = '<i class="fas fa-search me-1"></i>Search';
        }
        return;
    }
    
    // Make AJAX request to scan endpoint - FIXED ROUTE
    fetch(window.location.origin + '/barcode/scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            barcode_number: barcodeNumber
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            const item = data.data;
            displaySuccessResult(item, barcodeNumber);
        } else {
            displayErrorResult(data.message || 'The barcode number you entered was not found in our database.');
        }
    })
    .catch(error => {
        console.error('Scan error:', error);
        displayErrorResult('Unable to connect to the server. Please check your internet connection and try again.');
    })
    .finally(() => {
        // Re-enable button
        if (scanButton) {
            scanButton.disabled = false;
            scanButton.innerHTML = '<i class="fas fa-search me-1"></i>Search';
        }
    });
}

// FIXED: Display function to match PHP controller data structure
function displaySuccessResult(item, barcodeNumber) {
    const resultDiv = document.getElementById('scanResult');
    if (!resultDiv) return;

    // Format expiry date status
    let expiryStatus = '';
    let expiryClass = 'text-success';
    if (item.expiry_date) {
        const expiryDate = new Date(item.expiry_date);
        const today = new Date();
        const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
        
        if (daysUntilExpiry < 0) {
            expiryStatus = '(EXPIRED)';
            expiryClass = 'text-danger';
        } else if (daysUntilExpiry <= 7) {
            expiryStatus = '(Expiring Soon)';
            expiryClass = 'text-warning';
        }
    }

    // Status badge color
    let statusBadge = 'badge-success';
    if (item.status === 'inactive') statusBadge = 'badge-secondary';
    else if (item.status === 'damaged') statusBadge = 'badge-danger';
    else if (item.status === 'expired') statusBadge = 'badge-warning';

    resultDiv.innerHTML = `
        <div class="alert alert-success">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2 text-success"></i>
                    <strong>Barcode Found!</strong>
                </div>
                <span class="badge ${statusBadge}">${item.status.toUpperCase()}</span>
            </div>
            
            <div class="row g-2">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Barcode:</strong> <code>${barcodeNumber}</code></p>
                    <p class="mb-1"><strong>Material:</strong> ${item.material_name || 'N/A'}</p>
                    <p class="mb-1"><strong>Code:</strong> ${item.material_code || 'N/A'}</p>
                    <p class="mb-1"><strong>Batch:</strong> ${item.batch_number || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Supplier:</strong> ${item.supplier || 'N/A'}</p>
                    <p class="mb-1"><strong>Quantity:</strong> ${item.quantity || '0'}</p>
                    <p class="mb-1"><strong>Weight:</strong> ${item.weight || '0'} kg</p>
                    <p class="mb-1"><strong>Price:</strong> $${item.unit_price || '0.00'}</p>
                </div>
                <div class="col-12">
                    <p class="mb-1"><strong>Storage:</strong> ${item.storage_location || 'N/A'}</p>
                    <p class="mb-1"><strong>Quality:</strong> Grade ${item.quality_grade || 'N/A'}</p>
                    <p class="mb-1"><strong>Expiry:</strong> 
                        <span class="${expiryClass}">
                            ${item.expiry_date || 'N/A'} ${expiryStatus}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Last Scanned:</strong> ${item.last_scanned_at || 'Never'}</p>
                    <p class="mb-0"><strong>Scan Count:</strong> ${item.scan_count || 0} times</p>
                </div>
            </div>
            
            ${item.view_url ? `
            <div class="mt-3">
                <a href="${item.view_url}" class="btn btn-sm btn-outline-primary" target="_blank">
                    <i class="fas fa-eye me-1"></i>View Details
                </a>
            </div>
            ` : ''}
        </div>
    `;
}

function displayErrorResult(message) {
    const resultDiv = document.getElementById('scanResult');
    if (!resultDiv) return;

    resultDiv.innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Not Found!</strong><br>
            ${message}
        </div>
    `;
}
  
</script>
@endpush