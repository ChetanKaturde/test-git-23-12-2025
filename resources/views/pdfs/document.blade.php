<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $document_type === 'quotation' ? 'Quotation' : 'Invoice' }} - {{ $document->number ?? $document->invoice_number }}</title>
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

        /* Header Section */
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

        .logo-section {
            text-align: right;
            flex: 0 0 auto;
            margin-left: 20px;
        }

        .logo {
            max-width: 120px;
            height: auto;
            padding: 4px;
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

        .company-details strong {
            color: #374151;
        }

        /* Document Title Section */
        .document-header {
            text-align: right;
            margin-bottom: 30px;
        }

        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: {{ $document_type === 'quotation' ? '#1e40af' : '#dc2626' }};
            margin-bottom: 10px;
        }

        .document-details {
            color: #6b7280;
        }

        .document-details strong {
            color: #374151;
        }

        /* Bill To Section */
        .bill-to {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }

        .bill-to-label {
            font-weight: bold;
            color: {{ $document_type === 'quotation' ? '#1e40af' : '#dc2626' }};
            font-size: 12pt;
            margin-bottom: 10px;
        }

        .customer-name {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }

        .customer-details {
            color: #6b7280;
            line-height: 1.5;
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

        /* Totals Section */
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
            color: {{ $document_type === 'quotation' ? '#1e40af' : '#dc2626' }};
            font-size: 12pt;
        }

        .total-row .amount {
            color: {{ $document_type === 'quotation' ? '#4f46e5' : '#dc2626' }};
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
            color: {{ $document_type === 'quotation' ? '#1e40af' : '#dc2626' }};
            margin-bottom: 10px;
            font-size: 12pt;
        }

        .terms-content {
            color: #6b7280;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 9pt;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        /* Currency Symbol */
        .currency {
            font-weight: 600;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header Section -->
        <div class="header">
            <div class="company-section">
                @if(!$business->logo_path || !file_exists(public_path('storage/' . $business->logo_path)))
                    <div class="company-name">{{ $business->name ?? 'Your Business Name' }}</div>
                @endif

                <div class="company-details">
                    @if($business->logo_path && file_exists(public_path('storage/' . $business->logo_path)))
                        <div style="font-size: 16pt; font-weight: bold; color: #1e40af; margin-bottom: 8px;">
                            {{ $business->name ?? 'Your Business Name' }}
                        </div>
                    @endif

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

                    @if($business->pan)
                        <div><strong>PAN:</strong> {{ $business->pan }}</div>
                    @endif
                </div>
            </div>

            @if($business->logo_path && file_exists(public_path('storage/' . $business->logo_path)))
                <div class="logo-section">
                    <img src="{{ public_path('storage/' . $business->logo_path) }}" alt="Company Logo" class="logo">
                </div>
            @endif
        </div>

        <!-- Document Header -->
        <div class="document-header">
            <div class="document-title">{{ strtoupper($document_type) }}</div>
            <div class="document-details">
                <div><strong>{{ $document_type === 'quotation' ? 'Quote' : 'Invoice' }} No:</strong> {{ $document->number ?? $document->invoice_number }}</div>
                <div><strong>Date:</strong> {{ $document->created_at ? $document->created_at->format('d M Y') : ($document->issue_date ? $document->issue_date->format('d M Y') : now()->format('d M Y')) }}</div>
                @if($document_type === 'quotation')
                    <div><strong>Valid Until:</strong> {{ $document->valid_until ? $document->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}</div>
                @else
                    <div><strong>Due Date:</strong> {{ $document->due_date ? $document->due_date->format('d M Y') : null }}</div>
                @endif
            </div>
        </div>

        <!-- Bill To Section -->
        <div class="bill-to">
            <div class="bill-to-label">Bill To:</div>
            <div class="customer-name">{{ $customer->name ?? 'Sample Customer Pvt Ltd' }}</div>
            <div class="customer-details">
                <div>{{ $customer->address ?? 'Customer Address, City, State - PIN' }}</div>
                @if(isset($customer->phone) && $customer->phone)
                    <div><strong>Phone:</strong> {{ $customer->phone }}</div>
                @endif
                @if(isset($customer->email) && $customer->email)
                    <div><strong>Email:</strong> {{ $customer->email }}</div>
                @endif
                @if(isset($customer->gstin) && $customer->gstin)
                    <div><strong>GSTIN:</strong> {{ $customer->gstin }}</div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 8%;" class="text-center">Qty</th>
                    <th style="width: 8%;" class="text-center">Unit</th>
                    @if($document->pdf_options['show_list_price'] ?? false)
                        <th style="width: 12%;" class="text-right">List Price</th>
                    @endif
                    @if($document->pdf_options['show_discount'] ?? false)
                        <th style="width: 8%;" class="text-center">Disc %</th>
                    @endif
                    <th style="width: 12%;" class="text-right">Net Price</th>
                    @if($document->pdf_options['show_hsn'] ?? false)
                        <th style="width: 10%;" class="text-center">HSN</th>
                    @endif
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($items) && count($items) > 0)
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->description ?? 'Sample Product/Service' }}</td>
                        <td class="text-center">{{ $item->quantity ?? 1 }}</td>
                        <td class="text-center">{{ ucfirst($item->unit ?? 'piece') }}</td>
                        @if($document->pdf_options['show_list_price'] ?? false)
                            <td class="text-right">
                                <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($item->list_price ?? $item->unit_price ?? 1000, 2) }}
                            </td>
                        @endif
                        @if($document->pdf_options['show_discount'] ?? false)
                            <td class="text-center">{{ $item->discount_percentage ?? 0 }}%</td>
                        @endif
                        <td class="text-right">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($item->net_price ?? $item->unit_price ?? 1000, 2) }}
                        </td>
                        @if($document->pdf_options['show_hsn'] ?? false)
                            <td class="text-center">{{ $item->hsn_code ?? '-' }}</td>
                        @endif
                        <td class="text-right">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($item->total ?? 1180, 2) }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td>Sample Product/Service</td>
                        <td class="text-center">2</td>
                        <td class="text-center">Piece</td>
                        @if($document->pdf_options['show_list_price'] ?? false)
                            <td class="text-right">
                                <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>5,000.00
                            </td>
                        @endif
                        @if($document->pdf_options['show_discount'] ?? false)
                            <td class="text-center">0%</td>
                        @endif
                        <td class="text-right">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>5,000.00
                        </td>
                        @if($document->pdf_options['show_hsn'] ?? false)
                            <td class="text-center">998311</td>
                        @endif
                        <td class="text-right">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>10,000.00
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">
                        <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($document->subtotal ?? 10000, 2) }}
                    </td>
                </tr>
                @if($document->pdf_options['show_tax_breakdown'] ?? false)
                    @php
                        $totalCgst = 0;
                        $totalSgst = 0;
                        $totalIgst = 0;
                        if(isset($items)) {
                            foreach($items as $item) {
                                $totalCgst += $item->cgst_amount ?? 0;
                                $totalSgst += $item->sgst_amount ?? 0;
                                $totalIgst += $item->igst_amount ?? 0;
                            }
                        }
                    @endphp
                    @if($totalCgst > 0)
                    <tr>
                        <td class="label">CGST:</td>
                        <td class="amount">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($totalCgst, 2) }}
                        </td>
                    </tr>
                    @endif
                    @if($totalSgst > 0)
                    <tr>
                        <td class="label">SGST:</td>
                        <td class="amount">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($totalSgst, 2) }}
                        </td>
                    </tr>
                    @endif
                    @if($totalIgst > 0)
                    <tr>
                        <td class="label">IGST:</td>
                        <td class="amount">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($totalIgst, 2) }}
                        </td>
                    </tr>
                    @endif
                @else
                    @if($document->tax_amount > 0)
                    <tr>
                        <td class="label">Tax:</td>
                        <td class="amount">
                            <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format($document->tax_amount ?? 1800, 2) }}
                        </td>
                    </tr>
                    @endif
                @endif
                <tr class="total-row">
                    <td class="label">Grand Total:</td>
                    <td class="amount">
                        <span class="currency">{{ $business->currency === 'INR' ? '₹' : '$' }}</span>{{ number_format(($document->subtotal ?? 10000) + ($document->tax_amount ?? 1800), 2) }}
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

        <!-- Footer -->
        <div class="footer">
            <div>Page 1 of 1</div>
            <div style="margin-top: 5px;">© 2025 Monitorbizz — All rights reserved</div>
        </div>
    </div>
</body>
</html>