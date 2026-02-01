<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation - {{ $quotation->number ?? 'QUO-0001' }}</title>
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
            line-height: 1.4;
            color: #333;
            font-size: 12px;
        }

        .quotation-container {
            width: 100%;
            background: white;
        }

        .quotation-header {
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

        .quotation-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .company-logo {
            width: 120px;
            height: auto;
            margin-bottom: 10px;
            background: white;
            padding: 5px;
            border-radius: 4px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            color: white;
        }

        .company-details {
            font-size: 11px;
            line-height: 1.6;
            color: #f8f9fa;
        }

        .quotation-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        .quotation-subtitle {
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

        .customer-section {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
        }

        .customer-flex {
            display: table;
            width: 100%;
        }

        .bill-to {
            display: table-cell;
            width: 100%;
            vertical-align: top;
            padding-right: 15px;
        }

        .customer-title {
            color: #6f42c1;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .customer-info {
            line-height: 1.6;
            font-size: 11px;
        }

        .customer-info p {
            margin-bottom: 3px;
        }

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
            font-weight: bold;
            text-align: left;
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

        .amount-words {
            font-size: 13px;
            font-style: italic;
            margin-top: 8px;
            opacity: 0.9;
        }

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

        .quote-note {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 15px 20px;
            border-radius: 4px;
        }

        .quote-note h4 {
            color: #0c5460;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .quote-note p {
            color: #0c5460;
            font-size: 11px;
            line-height: 1.5;
        }

        .validity-badge {
            background: #28a745;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="quotation-container">
        <!-- Header Section -->
        <div class="quotation-header">
            <div class="header-flex">
                <div class="company-section">
                    @if($business->logo_path && file_exists(public_path($business->logo_path)))
                        <img src="{{ public_path($business->logo_path) }}" alt="Company Logo" class="company-logo">
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
                            GSTIN: {{ $business->gstin }}<br>
                        @endif
                        @if($business->pan)
                            PAN: {{ $business->pan }}
                        @endif
                    </div>
                </div>
                <div class="quotation-section">
                    <div class="quotation-title">QUOTATION</div>
                    <div class="quotation-subtitle">Price Estimate</div>
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="meta-section">
            <table class="meta-table">
                <tr>
                    <td class="meta-label">Quote No:</td>
                    <td class="meta-value highlight-value">{{ $quotation->number ?? 'QUO-2526-0001' }}</td>
                    <td class="meta-label" style="width: 25%;">Quote Date:</td>
                    <td class="meta-value">{{ isset($quotation->created_at) ? $quotation->created_at->format('d F Y') : now()->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Valid Until:</td>
                    <td class="meta-value">
                        <span class="validity-badge">{{ isset($quotation->valid_until) ? $quotation->valid_until->format('d F Y') : now()->addDays(30)->format('d F Y') }}</span>
                    </td>
                    <td class="meta-label">Status:</td>
                    <td class="meta-value">
                        {{ ucfirst($quotation->status ?? 'Pending') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer Information -->
        <div class="content-section">
            <div class="section-title">Customer Information</div>
            <div class="customer-section">
                <div class="customer-flex">
                    <div class="bill-to">
                        <div class="customer-title">Quote For:</div>
                        <div class="customer-info">
                            <p><strong>{{ $customer->name ?? 'Sample Customer Pvt Ltd' }}</strong></p>
                            <p>{{ $customer->address ?? 'Customer Address, City, State - PIN' }}</p>
                            @if(isset($customer->phone) && $customer->phone)
                                <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                            @endif
                            @if(isset($customer->email) && $customer->email)
                                <p><strong>Email:</strong> {{ $customer->email }}</p>
                            @endif
                            @if(isset($customer->gstin) && $customer->gstin)
                                <p><strong>GSTIN:</strong> {{ $customer->gstin }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Details -->
        <div class="content-section">
            <div class="section-title">Quotation Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Description</th>
                        <th style="width: 8%;" class="text-center">Qty</th>
                        <th style="width: 8%;" class="text-center">Unit</th>
                        <th style="width: 12%;" class="text-right">Rate</th>
                        <th style="width: 8%;" class="text-center">Disc %</th>
                        <th style="width: 12%;" class="text-right">Tax</th>
                        <th style="width: 15%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($items) && count($items) > 0)
                        @foreach($items as $item)
                        <tr>
                            <td><strong>{{ $item->description ?? 'Sample Product/Service' }}</strong></td>
                            <td class="text-center">{{ $item->quantity ?? 1 }}</td>
                            <td class="text-center">{{ ucfirst($item->unit ?? 'piece') }}</td>
                            <td class="text-right">{{ number_format($item->unit_price ?? 1000, 2) }} Rs.</td>
                            <td class="text-center">{{ $item->discount_percentage ?? 0 }}%</td>
                            <td class="text-right">{{ $item->tax_rate ?? 18 }}%</td>
                            <td class="text-right currency">{{ number_format($item->total ?? 1180, 2) }} Rs.</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td><strong>Sample Product/Service</strong></td>
                            <td class="text-center">2</td>
                            <td class="text-center">Piece</td>
                            <td class="text-right">5,000.00 Rs.</td>
                            <td class="text-center">0%</td>
                            <td class="text-right">18%</td>
                            <td class="text-right currency">10,000.00 Rs.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Subtotal:</td>
                    <td class="summary-value currency">{{ number_format($quotation->subtotal ?? 10000, 2) }} Rs.</td>
                </tr>
                @if(($quotation->discount_amount ?? 0) > 0)
                <tr>
                    <td class="summary-label">Discount:</td>
                    <td class="summary-value currency">-{{ number_format($quotation->discount_amount ?? 0, 2) }} Rs.</td>
                </tr>
                @endif
                <tr>
                    <td class="summary-label">Tax:</td>
                    <td class="summary-value currency">{{ number_format($quotation->tax_amount ?? 1800, 2) }} Rs.</td>
                </tr>
            </table>
        </div><br><br><br><br><br>

        <!-- Grand Total -->
        <div class="grand-total-section">
            <div class="grand-total-title">Grand Total</div>
            <div class="grand-total-amount">{{ number_format($quotation->total ?? 11800, 2) }} Rs.</div>
            <div class="amount-words">
                @php
                    function numberToWords($number) {
                        $ones = array(
                            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
                            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
                            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
                            18 => 'Eighteen', 19 => 'Nineteen'
                        );
                        
                        $tens = array(
                            0 => '', 2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
                            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
                        );
                        
                        if ($number < 20) {
                            return $ones[$number];
                        } elseif ($number < 100) {
                            return $tens[intval($number / 10)] . ' ' . $ones[$number % 10];
                        } elseif ($number < 1000) {
                            return $ones[intval($number / 100)] . ' Hundred ' . numberToWords($number % 100);
                        } elseif ($number < 100000) {
                            return numberToWords(intval($number / 1000)) . ' Thousand ' . numberToWords($number % 1000);
                        } elseif ($number < 10000000) {
                            return numberToWords(intval($number / 100000)) . ' Lakh ' . numberToWords($number % 100000);
                        } else {
                            return numberToWords(intval($number / 10000000)) . ' Crore ' . numberToWords($number % 10000000);
                        }
                    }
                    
                    $amount = intval($quotation->total ?? 11800);
                    $words = numberToWords($amount);
                @endphp
                Amount in Words: Rupees {{ trim($words) }} Only
            </div>
        </div>

        <!-- Quote Note -->
        <div class="quote-note">
            <h4>Important Note</h4>
            <p>This quotation is valid for 30 days from the date of issue. Prices are subject to change without prior notice. Please confirm your order within the validity period to secure these rates.</p>
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
            <p class="thank-you">Thank you for considering {{ $business->name ?? 'our business' }}!</p>
            <p>For any queries regarding this quotation, please contact us at {{ $business->email ?? 'contact@business.com' }}</p>
            <p style="margin-top: 15px; font-size: 10px;">
                This is a computer-generated quotation and does not require a physical signature.
            </p>
            <p style="margin-top: 10px;">© 2026-27 {{ $business->name ?? 'Monitorbizz' }} — All rights reserved</p>
        </div>
    </div>
</body>
</html>