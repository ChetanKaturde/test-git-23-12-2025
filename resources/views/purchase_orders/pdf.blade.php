<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Order PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
    body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 11px;
    line-height: 1.4;
    color: #333;
    background: #fff;
    padding: 20px;
}

        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header .po-number {
            font-size: 17px;
            color: #666;
            font-weight: 500;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        
        .info-section h3 {
            color: #007bff;
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: flex-start;
        }
        
        .info-label {
            font-weight: 800;
            color: #555;
            min-width: 120px;
            flex-shrink: 0;
        }
        
        .info-value {
            color: #333;
            flex: 1;
        }
        
        .status-section {
            grid-column: 1 / -1;
            text-align: center;
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
        }
        
        .badge {
            display: inline-block;
            padding: 8px 16px;
            color: #fff;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .bg-success { background: linear-gradient(135deg, #28a745, #20c997); }
        .bg-warning { background: linear-gradient(135deg, #ffc107, #fd7e14); color: #333; }
        .bg-info { background: linear-gradient(135deg, #17a2b8, #6f42c1); }
        .bg-danger { background: linear-gradient(135deg, #dc3545, #e83e8c); }
        .bg-primary { background: linear-gradient(135deg, #007bff, #6610f2); }
        .bg-secondary { background: linear-gradient(135deg, #6c757d, #495057); }
        
        .financial-summary {
              font-family: 'DejaVu Sans', sans-serif;
            background: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .financial-summary h3 {
              font-family: 'DejaVu Sans', sans-serif;
            color: #007bff;
            font-size: 17px;
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .financial-row {
              font-family: 'DejaVu Sans', sans-serif;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .financial-row:last-child {
            border-bottom: none;
            border-top: 2px solid #007bff;
            margin-top: 10px;
            padding-top: 15px;
            font-weight: bold;
            font-size: 15px;
            color: #007bff;
        }
        
        .amount {
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        
        .notes-section {
            margin-top: 30px;
            padding: 20px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 5px;
        }
        
        .notes-section h4 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .notes-content {
            color: #856404;
            line-height: 1.6;
            font-style: italic;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 15px;
        }
        
        .timestamp {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .timestamp-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .timestamp-row:last-child {
            margin-bottom: 0;
        }
        
        @media print {
            body { padding: 10px; }
            .container { max-width: 100%; }
            .info-grid { gap: 20px; }
        }
      
      <!--items-->
      
    </style>
  <style>
    .custom-table thead {
        background-color: #007bff;
        color: white;
    }

    .custom-table th,
    .custom-table td {
        vertical-align: middle;
        padding: 0.75rem;
        font-size: 0.95rem;
    }

    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.2s ease-in-out;
    }

    .custom-table td small {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .card-header h5 {
        font-weight: 600;
    }

    .table th.text-end,
    .table td.text-end {
        text-align: right;
    }

    .table th.text-center,
    .table td.text-center {
        text-align: center;
    }
</style>

</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>PURCHASE ORDER</h1>
            <div class="po-number">PO# {{ $purchaseOrder->po_number ?? 'N/A' }}</div>
        </div>

        <!-- Main Information Grid -->
        <div class="info-grid">
            <!-- Vendor Information -->
            <div class="info-section">
                <h3>Vendor Information</h3>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $purchaseOrder->vendor?->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Business:</span>
                    <span class="info-value">{{ $purchaseOrder->vendor?->business_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $purchaseOrder->vendor?->email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $purchaseOrder->vendor?->phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Company Address:</span>
                    <span class="info-value">{{ $purchaseOrder->vendor?->company_address ?? 'N/A' }}</span>
                </div>
               <div class="info-row">
                    <span class="info-label">Warehouse Address:</span>
                    <span class="info-value">{{ $purchaseOrder->vendor?->warehouse_address ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Order Details -->
            <div class="info-section">
                <h3>Order Details</h3>
                <div class="info-row">
                    <span class="info-label">PO Date:</span>
                    <span class="info-value">{{ optional($purchaseOrder->po_date)->format('M d, Y') ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ optional($purchaseOrder->order_date)->format('M d, Y') ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Expected Delivery:</span>
                    <span class="info-value">{{ optional($purchaseOrder->expected_delivery)->format('M d, Y') ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Mode:</span>
                    <span class="info-value">{{ ucfirst($purchaseOrder->payment_mode ?? 'N/A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Credit Days:</span>
                    <span class="info-value">{{ $purchaseOrder->credit_days ?? 0 }} days</span>
                </div>
            </div>
          
@if($purchaseOrder->items && $purchaseOrder->items->count())
    <div class="card mt-4 shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white rounded-top py-3 px-4">
            <h5 class="mb-0">🧾 Order Items ({{ $purchaseOrder->items->count() }} items)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 custom-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Item Name</th>
                            <th>Material Code</th>
                            <th class="text-end">Quantity</th>
                            <th class="text-center">Unit</th>
                            <th class="text-end">Unit Price (₹)</th>
                            <th class="text-end">GST Rate (%)</th>
                            <th class="text-end">Total Price (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $index => $item)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->material?->name ?? $item->item_name ?? 'N/A' }}</strong>
                                    @if($item->material?->description)
                                        <br><small class="text-muted">{{ $item->material->description }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->material?->code ?? '—' }}</td>
                                <td class="text-end">{{ number_format($item->quantity ?? 0, 2) }}</td>
                                <td class="text-center">{{ $item->material?->unit ?? '—' }}</td>
                                <td class="text-end">₹{{ number_format($item->unit_price ?? 0, 2) }}</td>
                                <td class="text-end">{{ number_format($item->gst_rate ?? 0, 2) }}</td>
                                <td class="text-end text-success fw-bold">₹{{ number_format($item->total_price ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif



            <!-- Status Section -->
            <div class="status-section">
                <h3 style="margin-bottom: 15px; color: #666;">Current Status</h3>
                <span class="badge bg-{{ match($purchaseOrder->status) {
                    'approved' => 'success',
                    'pending' => 'warning',
                    'received' => 'info',
                    'cancelled' => 'danger',
                    'ordered' => 'primary',
                    default => 'secondary'
                } }}">
                    {{ ucfirst($purchaseOrder->status ?? 'Unknown') }}
                </span>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="financial-summary">
            <h3>Financial Summary</h3>
            <div class="financial-row mt-4">
                <span>Subtotal Amount:</span>
                <span class="amount">{{ number_format($purchaseOrder->total_amount ?? 0, 2) }}</span>
            </div>
            <div class="financial-row">
                <span>GST Amount:</span>
                <span class="amount">{{ number_format($purchaseOrder->gst_amount ?? 0, 2) }}</span>
            </div>
            <div class="financial-row">
                <span>Shipping Cost:</span>
                <span class="amount">{{ number_format($purchaseOrder->shipping_cost ?? 0, 2) }}</span>
            </div>
            <div class="financial-row">
                <span>TOTAL AMOUNT:</span>
                <span class="amount">{{ number_format($purchaseOrder->final_amount ?? 0, 2) }}</span>
            </div>
        </div>

        <!-- Notes Section (if present) -->
        @if($purchaseOrder->notes)
        <div class="notes-section">
            <h4>Additional Notes</h4>
            <div class="notes-content">{{ $purchaseOrder->notes }}</div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="timestamp">
            <div class="timestamp-row">
                <strong>Created:</strong>
                <span>{{ $purchaseOrder->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
            </div>
            <div class="timestamp-row">
                <strong>Last Updated:</strong>
                <span>{{ $purchaseOrder->updated_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated purchase order. Please retain this document for your records.</p>
            <p>Generated on {{ date('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>