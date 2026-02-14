<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .business-info {
            flex: 1;
        }
        .receipt-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .payment-details {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="business-info">
            <h2>{{ $business->name }}</h2>
            <p>{{ $business->address }}</p>
            <p>Phone: {{ $business->phone }}</p>
            <p>Email: {{ $business->email }}</p>
        </div>
        <div class="receipt-title">
            PAYMENT RECEIPT
        </div>
    </div>

    <div class="customer-info">
        <h3>Customer Details</h3>
        <p><strong>Name:</strong> {{ $customer->name }}</p>
        @if($customer->address)
            <p><strong>Address:</strong> {{ $customer->address }}</p>
        @endif
        @if(isset($invoice) && ($invoice->customer_city || $invoice->customer_state))
            <p><strong>City, State:</strong> {{ $invoice->customer_city }}{{ $invoice->customer_city && $invoice->customer_state ? ', ' : '' }}{{ $invoice->customer_state }}</p>
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

    <div class="payment-details">
        <h3>Payment Details</h3>
        <p><strong>Receipt Number:</strong> {{ $payment->id }}</p>
        <p><strong>Payment Date:</strong> {{ $payment->created_at->format('d M Y') }}</p>
        <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
        <p><strong>Reference Number:</strong> {{ $payment->reference_no ?? 'N/A' }}</p>
        <p><strong>Amount Paid:</strong> Rs. {{ number_format($payment->amount, 2) }}</p>
        <p><strong>Notes:</strong> {{ $payment->notes ?? 'N/A' }}</p>
    </div>

    <div class="invoice-details">
        <h3>Invoice Reference Details</h3>
        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Invoice Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
        {{-- <p><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
        <p><strong>Balance Due:</strong> ${{ number_format($invoice->balance, 2) }}</p> --}}
    </div>

    <div class="footer">
        <p>Thank you for your payment. This is a computer-generated receipt.</p>
        <p>Generated on {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>