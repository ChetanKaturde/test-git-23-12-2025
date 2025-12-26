@extends('layouts.app')

@section('title', 'Print Barcodes')

@section('content')
<div class="container-fluid py-4">

    <!-- Dashboard Controls - Hidden when printing -->
    <div class="row mb-4 d-print-none">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">🖨️ Print Barcodes</h2>
            <div class="btn-group">
                <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                <button id="printButton" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Print Labels
                </button>
            </div>
        </div>
    </div>

    <!-- Print Settings - Hidden when printing -->
    <div class="row mb-4 d-print-none">
        <div class="col-12">
            <div class="card">
                <div class="card-body row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Label Size</label>
                        <select id="labelSize" class="form-select">
                            <option value="small">Small (2x1)</option>
                            <option value="medium" selected>Medium (3x2)</option>
                            <option value="large">Large (4x3)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Labels per Row</label>
                        <select id="labelsPerRow" class="form-select">
                            <option value="1">1</option>
                            <option value="2" selected>2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Include Details</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" id="includeDetails" checked>
                            <label class="form-check-label" for="includeDetails">Show Info</label>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button onclick="applyPrintSettings()" class="btn btn-outline-primary w-100">
                            <i class="fas fa-cog me-1"></i> Apply Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($barcodes->isEmpty())
        <div class="alert alert-warning d-print-none">No barcodes found to print.</div>
    @else
        <!-- Print Area - Only this section will print -->
        <div class="print-area">
            <div id="labelContainer" class="row g-2">
                @foreach($barcodes as $barcode)
                    <div class="label-item col-6">
                        <div class="barcode-label border p-3 text-center bg-white size-medium">

                            <!-- Barcode/QR Visual -->
                            <div class="barcode-visual mb-2">
                                @if(in_array($barcode->barcode_type, ['standard', 'both']))
                                    <img src="{{ route('barcode.generate-barcode', $barcode->barcode_number) }}" 
                                        alt="Barcode" class="barcode-img mt-4">
                                @endif
                                @if(in_array($barcode->barcode_type, ['qr', 'both']))
                                    <img src="{{ route('barcode.generate-qr', base64_encode($barcode->qr_code_data ?? '')) }}" 
                                        alt="QR Code" class="qr-img mt-4">
                                @endif
                            </div>

                            <!-- Barcode Number -->
                            <div class="barcode-number mb-2">
                                <strong>{{ $barcode->barcode_number }}</strong>
                            </div>

                            <!-- Material Details -->
                            <div class="material-details">
                                <div class="material-name">{{ $barcode->material_name ?? '—' }}</div>
                                <div class="material-code">{{ $barcode->material_code ?? '—' }}</div>
                                <div class="batch-info">
                                    Batch: {{ $barcode->batch->batch_number ?? 'N/A' }}
                                </div>
                                @if($barcode->supplier_name && $barcode->supplier_name !== '—')
                                    <div class="supplier-info">
                                        {{ $barcode->supplier_name }}
                                    </div>
                                @endif
                                @if($barcode->expiry_date)
                                    <div class="expiry-info">
                                        Exp: {{ $barcode->expiry_date->format('d/m/Y') }}
                                    </div>
                                @endif
                                @if($barcode->storage_location && $barcode->storage_location !== '—')
                                    <div class="location-info">
                                        📍 {{ $barcode->storage_location }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Inline JavaScript to ensure it loads -->
<script>
// Enhanced print function to ensure only barcode labels print
function printLabels() {
    // Create a new window with only the barcode content
    const printArea = document.querySelector('.print-area');
    if (!printArea) {
        alert('No barcodes found to print');
        return;
    }
    
    // Create print content
    const printContent = `
        <!DOCTYPE html>
        <html>
<head>
    <title>Print Barcodes</title>
       <style>
        /* Import Google Fonts for better typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: white;
            padding: 0.5in;
            line-height: 1.4;
        }
        
        /* Print-specific styles */
        @media print {
            body {
                padding: 0.25in;
                font-size: 12pt;
            }
            
            .row {
                margin: 0;
            }
            
            .label-item {
                padding: 0.1in;
            }
            
            .barcode-label {
           margin-top:75px;
                page-break-inside: avoid;
                break-inside: avoid;
                margin-bottom: 0.15in;
            }
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            margin: 0 -0.1in;
            gap: 0.2in;
        }
        
        .label-item {
            padding: 0.1in;
            flex: 0 0 auto;
            display: flex;
            justify-content: center;
        }
        
        .barcode-label {
            border: 3px solid #000;
            padding: 0.3in;
            text-align: center;
            background: white;
            page-break-inside: avoid;
            margin-bottom: 0.2in;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        /* Enhanced size options */
        .barcode-label.size-small { 
            width: 3in; 
            height: 2in; 
            font-size: 10pt; 
            padding: 0.2in; 
        }
        
        .barcode-label.size-medium { 
            width: 4in; 
            height: 3in; 
            font-size: 12pt; 
            padding: 0.3in; 
        }
        
        .barcode-label.size-large { 
            width: 5in; 
            height: 4in; 
            font-size: 14pt; 
            padding: 0.4in; 
        }
        
        .barcode-label.size-xlarge { 
            width: 6in; 
            height: 4.5in; 
            font-size: 16pt; 
            padding: 0.5in; 
        }
        
        /* Barcode visual elements */
        .barcode-visual {
            margin-bottom: 0.15in;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .barcode-img { 
            max-width: 150%; 
            height: auto; 
            max-height: 1.5in;
            min-height: 0.8in;
           margint-top:20px;
        }
        
        .qr-img { 
            width: 1in; 
            height: 1in; 
            max-width: 1in;
            max-height: 1in;
        }
        
        /* Typography improvements */
        .barcode-number { 
            font-family: 'JetBrains Mono', 'Courier New', Consolas, monospace; 
            font-size: 1.8em; 
            font-weight: 600;
            color: #000;
            margin-bottom: 0.15in;
            letter-spacing: 0.05em;
            word-spacing: 0.1em;
        }
        
        .material-name { 
            font-family: 'Inter', sans-serif;
            font-weight: 600; 
            font-size: 1.1em; 
            margin-bottom: 0.08in;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        
        .material-code { 
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.95em;
            font-weight: 500;
            line-height: 1.3;
            margin-bottom: 0.08in;
            color: #444;
            background: #f8f9fa;
            padding: 0.05in 0.1in;
            border-radius: 3px;
            display: inline-block;
        }
        
        .batch-info, .supplier-info, .expiry-info, .location-info {
            font-family: 'Inter', sans-serif;
            font-size: 0.9em;
            font-weight: 500;
            line-height: 1.4;
            margin-bottom: 0.06in;
            padding: 0.03in 0;
        }
        
        .batch-info {
            color: #198754;
        }
        
        .supplier-info {
            color: #6f42c1;
        }
        
        .expiry-info {
            color: #dc3545;
            font-weight: 600;
            background: #fff5f5;
            padding: 0.05in 0.08in;
            border-radius: 3px;
            border-left: 3px solid #dc3545;
        }
        
        .location-info {
            color: #0d6efd;
            font-weight: 600;
            background: #f0f8ff;
            padding: 0.05in 0.08in;
            border-radius: 3px;
            border-left: 3px solid #0d6efd;
        }
        
        /* Labels for info sections */
        .info-label {
            font-size: 0.75em;
            font-weight: 400;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-right: 0.1em;
        }
        
        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .row {
                flex-direction: column;
                align-items: center;
            }
            
            .label-item {
                flex: none;
                width: 100%;
                max-width: none;
                display: flex;
                justify-content: center;
            }
        }
        
        /* Print page breaks */
        @media print {
            .barcode-label {
                box-shadow: none;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
        
        /* Additional utility classes */
        .text-center { text-align: center; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .font-bold { font-weight: 600; }
        .mb-sm { margin-bottom: 0.05in; }
        .mb-md { margin-bottom: 0.1in; }
        .mb-lg { margin-bottom: 0.15in; }
    </style>
        </head>
        <body>
            ${printArea.outerHTML}
        </body>
        </html>
    `;
    
    // Open print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for content to load, then print
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    };
}

function applyPrintSettings() {
    const labelSize = document.getElementById('labelSize').value;
    const labelsPerRow = parseInt(document.getElementById('labelsPerRow').value);
    const includeDetails = document.getElementById('includeDetails').checked;

    // Update label sizes
    const labels = document.querySelectorAll('.barcode-label');
    labels.forEach(label => {
        label.className = `barcode-label border p-3 text-center bg-white size-${labelSize}`;
    });

    // Update labels per row
    const labelItems = document.querySelectorAll('.label-item');
    labelItems.forEach(item => {
        item.className = `label-item col-${12 / labelsPerRow}`;
    });

    // Show/hide material details
    document.querySelectorAll('.material-details').forEach(detail => {
        detail.style.display = includeDetails ? 'block' : 'none';
    });
}

// Apply settings and bind events on page load
document.addEventListener('DOMContentLoaded', function() {
    applyPrintSettings();
    
    // Bind print button
    const printButton = document.getElementById('printButton');
    if (printButton) {
        printButton.addEventListener('click', printLabels);
    }
});
</script>
@endsection

@push('styles')
<style>
/* Print-specific styles */
@media print {
    /* Hide ALL dashboard elements */
    .d-print-none { 
        display: none !important; 
    }
    
    /* Hide navigation, sidebar, header, footer */
    nav, .navbar, .sidebar, header, footer, .main-header, .main-sidebar, .content-header {
        display: none !important;
    }
    
    /* Hide Laravel's default layout elements */
    .app-header, .app-sidebar, .app-content, .wrapper {
        display: none !important;
    }
    
    /* Show only the print content */
    .print-area {
        display: block !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 15px !important;
        background: white !important;
    }
    
    /* Remove all margins and padding from body/html */
    * {
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        width: 100% !important;
        height: 100% !important;
    }
    
    /* Hide everything except print area */
    body > *:not(.print-area) {
        display: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
    }
    
    /* Force only print-area to be visible */
    .container-fluid > *:not(.print-area) {
        display: none !important;
    }
    
    /* Label styling for print */
    .barcode-label { 
        border: 2px solid #000 !important; 
        page-break-inside: avoid !important; 
        margin-bottom: 10px !important;
        background: white !important;
        box-shadow: none !important;
    }
    
    /* Ensure proper spacing between labels */
    .label-item {
        margin-bottom: 15px !important;
    }
    
    /* Force background colors and borders to print */
    .bg-white {
        background: white !important;
    }
    
    .border {
        border: 2px solid #000 !important;
    }
    
    /* Hide Bootstrap's row gutters in print */
    .row {
        margin: 0 !important;
    }
    
    .g-2 > * {
        padding: 5px !important;
    }
}

/* Screen display styles */
@media screen {
    .print-area {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }
}

/* Label size classes */
.barcode-label.size-small { 
    width: 2in; 
    height: 1in; 
    font-size: 8px; 
    padding: 0.25in !important;
}

.barcode-label.size-medium { 
    width: 3in; 
    height: 2in; 
    font-size: 10px; 
    padding: 0.3in !important;
}

.barcode-label.size-large { 
    width: 4in; 
    height: 3in; 
    font-size: 12px; 
    padding: 0.4in !important;
}

/* Barcode and QR code images */
.barcode-img { 
    max-width: 100%; 
    height: auto; 
    max-height: 0.8in;
}

.qr-img { 
    max-width: 60px; 
    height: 60px; 
}

.size-small .qr-img { 
    max-width: 40px; 
    height: 40px; 
}

.size-large .qr-img { 
    max-width: 80px; 
    height: 80px; 
}

/* Text styling */
.barcode-number { 
    font-family: 'Courier New', monospace; 
    font-size: 1em; 
    font-weight: bold;
    color: #000 !important;
    margin-bottom: 8px;
}

.material-name { 
    font-weight: bold; 
    font-size: 1em; 
    margin-bottom: 4px;
    color: #000 !important;
}

.material-code { 
    font-size: 0.9em;
    line-height: 1.3;
    margin-bottom: 4px;
    color: #666 !important;
}

.batch-info, .supplier-info, .expiry-info, .location-info {
    font-size: 0.85em;
    line-height: 1.3;
    margin-bottom: 3px;
    color: #333 !important;
}

.expiry-info {
    color: #d63384 !important;
    font-weight: 500;
}

.location-info {
    color: #0d6efd !important;
}

/* Responsive adjustments */
.size-small .material-details {
    font-size: 0.7em;
}

.size-large .material-details {
    font-size: 0.9em;
}
</style>
@endpush

@push('scripts')
<script>
// Enhanced print function to ensure only barcode labels print
function printLabels() {
    // Create a new window with only the barcode content
    const printArea = document.querySelector('.print-area');
    if (!printArea) {
        alert('No barcodes found to print');
        return;
    }
    
    // Get the current styles
    const styles = Array.from(document.styleSheets)
        .map(styleSheet => {
            try {
                return Array.from(styleSheet.cssRules)
                    .map(rule => rule.cssText)
                    .join('\n');
            } catch (e) {
                return '';
            }
        })
        .join('\n');
    
    // Create print content
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print Barcodes</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
                
                body {
                    font-family: Arial, sans-serif;
                    background: white;
                    padding: 15px;
                }
                
                .row {
                    display: flex;
                    flex-wrap: wrap;
                    margin: 0 -5px;
                }
                
                .label-item {
                    padding: 5px;
                    flex: 0 0 50%;
                    max-width: 50%;
                }
                
                .barcode-label {
                    border: 2px solid #000;
                    padding: 20px;
                    text-align: center;
                    background: white;
                    page-break-inside: avoid;
                    margin-bottom: 15px;
                }
                
                .barcode-label.size-small { width: 2in; height: 1in; font-size: 8px; padding: 0.25in; }
                .barcode-label.size-medium { width: 3in; height: 2in; font-size: 10px; padding: 0.3in; }
                .barcode-label.size-large { width: 4in; height: 3in; font-size: 12px; padding: 0.4in; }
             .barcode-img { 
    max-width: 100%;
    height: auto;
    max-height: 0.8in; /* 0.10in is too small to be readable; increase if necessary */
    display: block;
    margin: 0 auto;
}

.qr-img { 
    max-width: 60px;
    height: 60px;
    display: block;
    margin: 4px auto 0 auto;
}
                
                .barcode-number { 
                    font-family: 'Courier New', monospace; 
                    font-size: 1em; 
                    font-weight: bold;
                    color: #000;
                    margin-bottom: 8px;
                }
                
                .material-name { 
                    font-weight: bold; 
                    font-size: 1em; 
                    margin-bottom: 4px;
                    color: #000;
                }
                
                .material-code { 
                    font-size: 0.9em;
                    line-height: 1.3;
                    margin-bottom: 4px;
                    color: #666;
                }
                
                .batch-info, .supplier-info, .expiry-info, .location-info {
                    font-size: 0.85em;
                    line-height: 1.3;
                    margin-bottom: 3px;
                    color: #333;
                }
                
                .expiry-info {
                    color: #d63384;
                    font-weight: 500;
                }
                
                .location-info {
                    color: #0d6efd;
                }
                
                .barcode-visual {
                    margin-bottom: 8px;
                }
            </style>
        </head>
        <body>
            ${printArea.outerHTML}
        </body>
        </html>
    `;
    
    // Open print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for content to load, then print
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    };
}

function applyPrintSettings() {
    const labelSize = document.getElementById('labelSize').value;
    const labelsPerRow = parseInt(document.getElementById('labelsPerRow').value);
    const includeDetails = document.getElementById('includeDetails').checked;

    // Update label sizes
    const labels = document.querySelectorAll('.barcode-label');
    labels.forEach(label => {
        label.className = `barcode-label border p-3 text-center bg-white size-${labelSize}`;
    });

    // Update labels per row
    const labelItems = document.querySelectorAll('.label-item');
    labelItems.forEach(item => {
        item.className = `label-item col-${12 / labelsPerRow}`;
    });

    // Show/hide material details
    document.querySelectorAll('.material-details').forEach(detail => {
        detail.style.display = includeDetails ? 'block' : 'none';
    });
}

// Apply settings on page load
document.addEventListener('DOMContentLoaded', function() {
    applyPrintSettings();
});
</script>
@endpush