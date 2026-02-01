<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number ?? 'INV-0001' }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .page {
            width: 100%;
            background: white;
        }
        
        .header {
            background: #6f42c1;
            color: white;
            padding: 20px;
            position: relative;
        }
        
        .header-flex {
            display: table;
            width: 100%;
        }
        
        .company-section {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .logo-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
            background: white;
            padding: 5px;
            border-radius: 4px;
        }
        
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
        }
        
        .company-details {
            font-size: 11px;
            line-height: 1.6;
            color: #f8f9fa;
        }
        
        .document-title {
            font-size: 32px;
            font-weight: bold;
            color: white;
            margin-bottom: 5px;
        }
        
        .document-subtitle {
            font-size: 12px;
            color: #f8f9fa;
        }
        
        .meta-section {
            background: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #6f42c1;
        }
        
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .meta-table td {
            padding: 6px 8px;
            font-size: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .meta-label {
            font-weight: bold;
            color: #6f42c1;
            width: 25%;
        }
        
        .meta-value {
            color: #333;
        }
        
        .highlight-value {
            color: #6f42c1;
            font-weight: bold;
        }
        
        .content-section {
            padding: 20px;
            margin: 15px 0;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #6f42c1;
        }
        
        .bill-to {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
        }
        
        .bill-to-label {
            color: #6f42c1;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .customer-name {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }

        .customer-info {
            line-height: 1.6;
            font-size: 11px;
        }

        .customer-info p {
            margin-bottom: 3px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            border: 1px solid #ddd;
        }

        .items-table thead {
            background: #6f42c1;
            color: white;
        }

        .items-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #5a36a3;
        }

        .items-table td {
            padding: 10px 8px;
            font-size: 11px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .currency {
            font-weight: bold;
            color: #28a745;
        }

        .summary-section {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            margin: 15px 20px;
            width: 350px;
            margin-left: auto;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 8px 12px;
            font-size: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .summary-label {
            font-weight: bold;
            color: #6f42c1;
        }
        
        .summary-value {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        
        .grand-total-section {
            background: #6f42c1;
            color: white;
            padding: 20px;
            margin: 20px;
            text-align: center;
            border-radius: 4px;
        }
        
        .grand-total-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .grand-total-amount {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0;
        }

        /* Terms & Conditions */
        .terms-section {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
        }

        .terms-title {
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .terms-content {
            color: #6b7280;
            line-height: 1.6;
            font-size: 11px;
        }

        .footer {
            background: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }
        
        .footer p {
            margin: 6px 0;
            font-size: 11px;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header Section -->
        <div class="header">
            <div class="header-flex">
                <div class="company-section">
                    @if($business->logo_path && file_exists(public_path($business->logo_path)))
                        <img src="{{ public_path($business->logo_path) }}" alt="Company Logo" class="logo">
                    @endif

                    <div class="company-name">{{ $business->name ?? 'Your Business Name' }}</div>
                    <div class="company-details">
                        @if($business->legal_name && $business->legal_name !== $business->name)
                            <strong>Legal Name:</strong> {{ $business->legal_name }}<br>
                        @endif
                        {{ $business->address ?? 'Business Address' }}<br>
                        {{ $business->city ?? 'City' }}, {{ $business->state ?? 'State' }} {{-- $business->pin_code ?? '000000' --}}<br>
                        @if($business->phone)
                            Phone: {{ $business->phone }}<br>
                        @endif
                        @if($business->email)
                            Email: {{ $business->email }}<br>
                        @endif
                        @if($business->gstin)
                            GSTIN: {{ $business->gstin }}
                        @endif
                    </div>
                </div>
                
                <div class="logo-section">
                    <div class="document-title">INVOICE</div>
                    <div class="document-subtitle">Tax Invoice</div>
                </div>
            </div>
        </div>
        
        <!-- Meta Information -->
        <div class="meta-section">
            <table class="meta-table">
                <tr>
                    <td class="meta-label">Invoice No:</td>
                    <td class="meta-value highlight-value">{{ $invoice->invoice_number }}</td>
                    <td class="meta-label" style="width: 25%;">Issue Date:</td>
                    <td class="meta-value">{{ $invoice->issue_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Due Date:</td>
                    <td class="meta-value highlight-value">{{ $invoice->due_date->format('d M Y') }}</td>
                    <td class="meta-label">Status:</td>
                    <td class="meta-value">
                        {{ ucfirst($invoice->status ?? 'Pending') }}
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Customer Information -->
        <div class="content-section">
            <div class="section-title">Customer Information</div>
            <div class="bill-to">
                <div class="bill-to-label">Bill To:</div>
                <div class="customer-name">{{ $customer->name }}</div>
                <div class="customer-info">
                    @if($customer->address)
                        <p>{{ $customer->address }}</p>
                    @endif
                    @if($customer->phone)
                        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                    @endif
                    @if($customer->email)
                        <p><strong>Email:</strong> {{ $customer->email }}</p>
                    @endif
                    @if($customer->gstin)
                        <p><strong>GSTIN:</strong> {{ $customer->gstin }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        @if(isset($items) && count($items) > 0)
        <div class="content-section">
            <div class="section-title">Invoice Items</div>
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
                        <td><strong>{{ $item->description ?? 'Item' }}</strong></td>
                        <td class="text-center">{{ $item->quantity ?? 1 }}</td>
                        <td class="text-right">{{ number_format($item->unit_price ?? 0, 2) }} Rs.</td>
                        <td class="text-center">{{ $item->tax_rate ?? 0 }}%</td>
                        <td class="text-right currency">{{ number_format($item->total_price ?? 0, 2) }} Rs.</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Summary Section -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Subtotal:</td>
                    <td class="summary-value currency">{{ number_format($invoice->subtotal ?? $invoice->total_amount, 2) }} Rs.</td>
                </tr>
                @if($invoice->tax_amount > 0)
                <tr>
                    <td class="summary-label">Tax:</td>
                    <td class="summary-value currency">{{ number_format($invoice->tax_amount, 2) }} Rs.</td>
                </tr>
                @endif
            </table>
        </div><br><br><br><br>

        <!-- Grand Total -->
        <div class="grand-total-section">
            <div class="grand-total-title">Grand Total</div>
            <div class="grand-total-amount">{{ number_format($invoice->total_amount, 2) }} Rs.</div>
        </div>

        <!-- Terms & Conditions -->
        @if($business->terms_and_conditions)
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-content">{{ $business->terms_and_conditions }}</div>
        </div>
        @endif

        <div class="footer">
            <p class="thank-you">Thank you for your business!</p>
            <p>For any queries regarding this invoice, please contact us at {{ $business->email ?? 'contact@business.com' }}</p>
            <p style="margin-top: 15px; font-size: 10px;">
                This is a computer-generated invoice and does not require a physical signature.
            </p>
            <p style="margin-top: 10px;">© 2026-27 {{ $business->name ?? 'Monitorbizz' }} — All rights reserved</p>
        </div>
    </div>
</body>
</html>