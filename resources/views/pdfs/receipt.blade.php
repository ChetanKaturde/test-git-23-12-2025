<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
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

        .receipt-container {
            width: 100%;
            background: white;
        }

        /* ── Header ── */
        .receipt-header {
            background: #6f42c1;
            color: white;
            padding: 20px;
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

        .receipt-title-section {
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

        .receipt-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        .receipt-subtitle {
            font-size: 12px;
            color: #f8f9fa;
        }

        /* ── Meta Section ── */
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

        /* ── Content Sections ── */
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

        /* ── Customer & Payment Info Boxes ── */
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
        }

        .info-box-flex {
            display: table;
            width: 100%;
        }

        .info-col {
            display: table-cell;
            vertical-align: top;
            padding-right: 15px;
        }

        .info-col:last-child {
            padding-right: 0;
            padding-left: 15px;
            border-left: 1px solid #e9ecef;
        }

        .info-col-title {
            color: #6f42c1;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .info-col p {
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 3px;
            color: #333;
        }

        /* ── Payment Details Table ── */
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

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .currency {
            font-weight: bold;
            color: #28a745;
        }

        /* ── Payment Method Badge ── */
        .method-badge {
            background: #e9ecef;
            color: #495057;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* ── Grand Total ── */
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

        /* ── Invoice Reference ── */
        .invoice-ref-section {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 15px 20px;
            border-radius: 4px;
        }

        .invoice-ref-section h4 {
            color: #0c5460;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .invoice-ref-section p {
            color: #0c5460;
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 3px;
        }

        /* ── Footer ── */
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
    <div class="receipt-container">

        <!-- ── Header ── -->
        <div class="receipt-header">
            <div class="header-flex">
                <div class="company-section">
                    @if($business->logo_path && file_exists(public_path($business->logo_path)))
                        <img src="{{ public_path($business->logo_path) }}" alt="Company Logo" class="company-logo">
                    @endif
                    <div class="company-name">{{ $business->name }}</div>
                    <div class="company-details">
                        @if($business->legal_name && $business->legal_name !== $business->name)
                            <strong>Legal Name:</strong> {{ $business->legal_name }}<br>
                        @endif
                        {{ $business->address }}<br>
                        {{ $business->business_city ?? '' }}{{ ($business->business_city && $business->business_state) ? ', ' : '' }}{{ $business->business_state ?? '' }}<br>
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
                <div class="receipt-title-section">
                    <div class="receipt-title">RECEIPT</div>
                    <div class="receipt-subtitle">Payment Confirmation</div>
                </div>
            </div>
        </div>

        <!-- ── Meta Info ── -->
        <div class="meta-section">
            <table class="meta-table">
                <tr>
                    <td class="meta-label">Receipt No:</td>
                    <td class="meta-value highlight-value">#{{ $payment->id }}</td>
                    <td class="meta-label" style="width: 25%;">Payment Date:</td>
                    <td class="meta-value">{{ $payment->created_at->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Invoice Ref:</td>
                    <td class="meta-value highlight-value">{{ $invoice->invoice_number }}</td>
                    <td class="meta-label">Invoice Date:</td>
                    <td class="meta-value">{{ $invoice->created_at->format('d F Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- ── Customer & Payment Details Side by Side ── -->
        <div class="content-section">
            <div class="section-title">Details</div>
            <div class="info-box">
                <div class="info-box-flex">
                    <!-- Customer -->
                    <div class="info-col" style="width: 55%;">
                        <div class="info-col-title">Customer Information</div>
                        <p><strong>{{ $customer->name }}</strong></p>
                        @if($customer->address)
                            <p>{{ $customer->address }}</p>
                        @endif
                        @if(isset($invoice) && ($invoice->customer_city || $invoice->customer_state))
                            <p>{{ $invoice->customer_city }}{{ ($invoice->customer_city && $invoice->customer_state) ? ', ' : '' }}{{ $invoice->customer_state }}</p>
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
                    <!-- Payment -->
                    <div class="info-col" style="width: 45%;">
                        <div class="info-col-title">Payment Information</div>
                        <p><strong>Method:</strong> <span class="method-badge">{{ $payment->payment_method }}</span></p>
                        @if($payment->reference_no)
                            <p><strong>Reference No:</strong> {{ $payment->reference_no }}</p>
                        @endif
                        @if($payment->notes)
                            <p><strong>Notes:</strong> {{ $payment->notes }}</p>
                        @endif
                        <p><strong>Processed On:</strong> {{ $payment->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Payment Breakdown Table ── -->
        <div class="content-section">
            <div class="section-title">Payment Breakdown</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Description</th>
                        <th style="width: 25%;" class="text-center">Invoice Number</th>
                        <th style="width: 20%;" class="text-center">Payment Method</th>
                        <th style="width: 15%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Payment against invoice</strong><br>
                            <small style="color: #6b7280;">{{ $customer->name }}</small>
                        </td>
                        <td class="text-center">{{ $invoice->invoice_number }}</td>
                        <td class="text-center">{{ $payment->payment_method }}</td>
                        <td class="text-right currency">{{ number_format($payment->amount, 2) }} Rs.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ── Grand Total ── -->
        <div class="grand-total-section">
            <div class="grand-total-title">Amount Paid</div>
            <div class="grand-total-amount">{{ number_format($payment->amount, 2) }} Rs.</div>
            <div class="amount-words">
                @php
                    function receiptNumberToWords($number) {
                        $ones = [
                            0=>'',1=>'One',2=>'Two',3=>'Three',4=>'Four',5=>'Five',
                            6=>'Six',7=>'Seven',8=>'Eight',9=>'Nine',10=>'Ten',
                            11=>'Eleven',12=>'Twelve',13=>'Thirteen',14=>'Fourteen',
                            15=>'Fifteen',16=>'Sixteen',17=>'Seventeen',18=>'Eighteen',19=>'Nineteen'
                        ];
                        $tens = [0=>'',2=>'Twenty',3=>'Thirty',4=>'Forty',5=>'Fifty',6=>'Sixty',7=>'Seventy',8=>'Eighty',9=>'Ninety'];
                        if ($number < 20)       return $ones[$number];
                        if ($number < 100)      return trim($tens[intval($number/10)].' '.$ones[$number%10]);
                        if ($number < 1000)     return trim($ones[intval($number/100)].' Hundred '.receiptNumberToWords($number%100));
                        if ($number < 100000)   return trim(receiptNumberToWords(intval($number/1000)).' Thousand '.receiptNumberToWords($number%1000));
                        if ($number < 10000000) return trim(receiptNumberToWords(intval($number/100000)).' Lakh '.receiptNumberToWords($number%100000));
                        return trim(receiptNumberToWords(intval($number/10000000)).' Crore '.receiptNumberToWords($number%10000000));
                    }
                    $words = receiptNumberToWords(intval($payment->amount));
                @endphp
                Amount in Words: Rupees {{ trim($words) }} Only
            </div>
        </div>

        <!-- ── Invoice Reference Note ── -->
        <div class="invoice-ref-section">
            <h4>Invoice Reference</h4>
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Invoice Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
            <p>This receipt confirms payment received against the above invoice. Please retain this receipt for your records.</p>
        </div>

        <!-- ── Footer ── -->
        <div class="footer">
            <p class="thank-you">Thank you for your payment to {{ $business->name }}!</p>
            <p>For any queries regarding this receipt, please contact us at {{ $business->email }}</p>
            <p style="margin-top: 15px; font-size: 10px;">
                This is a computer-generated receipt and does not require a physical signature.
            </p>
            <p>Generated on {{ now()->format('d M Y H:i:s') }}</p>
            <p style="margin-top: 10px;">© 2026-27 {{ $business->name }} — All rights reserved</p>
        </div>

    </div>
</body>
</html>