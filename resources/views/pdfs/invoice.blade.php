<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number ?? 'INV-0001' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #374151;
            margin: 0;
            padding: 20px;
        }
        
        .page {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .company-section {
            flex: 1;
        }
        
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 8px;
        }
        
        .company-details {
            color: #6b7280;
            line-height: 1.5;
        }
        
        .document-header {
            text-align: right;
            margin-bottom: 30px;
        }
        
        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }
        
        .document-details {
            color: #6b7280;
        }
        
        .bill-to {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }
        
        .bill-to-label {
            font-weight: bold;
            color: #dc2626;
            font-size: 12pt;
            margin-bottom: 10px;
        }
        
        .customer-name {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #d1d5db;
        }

        .items-table th {
            background: #6b7280;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10pt;
        }

        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .totals-table .label {
            text-align: right;
            font-weight: 500;
            color: #6b7280;
        }
        
        .totals-table .amount {
            text-align: right;
            font-weight: 600;
            color: #374151;
        }
        
        .total-row {
            background: #f3f4f6;
            font-weight: bold;
        }
        
        .total-row .label {
            color: #dc2626;
            font-size: 12pt;
        }
        
        .total-row .amount {
            color: #dc2626;
            font-size: 12pt;
        }

        /* Terms & Conditions */
        .terms-section {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .terms-title {
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
            font-size: 12pt;
        }

        .terms-content {
            color: #6b7280;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 9pt;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .currency {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="company-section">
                <div class="company-name">{{ $business->name ?? 'Your Business Name' }}</div>
                <div class="company-details">
                    @if($business->legal_name && $business->legal_name !== $business->name)
                        <div><strong>Legal Name:</strong> {{ $business->legal_name }}</div>
                    @endif
                    <div>{{ $business->address ?? 'Business Address' }}</div>
                    <div>{{ $business->city ?? 'City' }}, {{ $business->state ?? 'State' }} - {{ $business->pin_code ?? '000000' }}</div>
                    @if($business->phone)
                        <div><strong>Phone:</strong> {{ $business->phone }}</div>
                    @endif
                    @if($business->email)
                        <div><strong>Email:</strong> {{ $business->email }}</div>
                    @endif
                    @if($business->gstin)
                        <div><strong>GSTIN:</strong> {{ $business->gstin }}</div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="document-header">
            <div class="document-title">INVOICE</div>
            <div class="document-details">
                <div><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Date:</strong> {{ $invoice->issue_date->format('d M Y') }}</div>
                <div><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</div>
            </div>
        </div>
        
        <div class="bill-to">
            <div class="bill-to-label">Bill To:</div>
            <div class="customer-name">{{ $customer->name }}</div>
            <div class="customer-details">
                @if($customer->address)
                    <div>{{ $customer->address }}</div>
                @endif
                @if($customer->phone)
                    <div><strong>Phone:</strong> {{ $customer->phone }}</div>
                @endif
                @if($customer->email)
                    <div><strong>Email:</strong> {{ $customer->email }}</div>
                @endif
                @if($customer->gstin)
                    <div><strong>GSTIN:</strong> {{ $customer->gstin }}</div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        @if(isset($items) && count($items) > 0)
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 15%;" class="text-right">Rate</th>
                    <th style="width: 10%;" class="text-center">Tax</th>
                    <th style="width: 15%;" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->description ?? 'Item' }}</td>
                    <td class="text-center">{{ $item->quantity ?? 1 }}</td>
                    <td class="text-right">
                        <span class="currency">₹</span>{{ number_format($item->unit_price ?? 0, 2) }}
                    </td>
                    <td class="text-center">{{ $item->tax_rate ?? 0 }}%</td>
                    <td class="text-right">
                        <span class="currency">₹</span>{{ number_format($item->total_price ?? 0, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">
                        <span class="currency">₹</span>{{ number_format($invoice->subtotal ?? $invoice->total_amount, 2) }}
                    </td>
                </tr>
                @if($invoice->tax_amount > 0)
                <tr>
                    <td class="label">Tax:</td>
                    <td class="amount">
                        <span class="currency">₹</span>{{ number_format($invoice->tax_amount, 2) }}
                    </td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="label">Total Amount:</td>
                    <td class="amount">
                        <span class="currency">₹</span>{{ number_format($invoice->total_amount, 2) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Terms & Conditions -->
        @if($business->terms_and_conditions)
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-content">{{ $business->terms_and_conditions }}</div>
        </div>
        @endif

        <div class="footer">
            <div>Page 1 of 1</div>
            <div style="margin-top: 5px;">© 2025 Monitorbizz — All rights reserved</div>
        </div>
    </div>
</body>
</html>