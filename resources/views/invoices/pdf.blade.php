<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { text-align: center; margin-bottom: 20px; }
        .invoice-details { margin-bottom: 20px; }
        .customer-details { margin-bottom: 20px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .totals { text-align: right; }
        .total-row { font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
        .row { display: flex; justify-content: space-between; }
        .col { flex: 1; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
    </div>

    <div class="company-info">
        @if(auth()->user()->business->logo_path)
            <img src="{{ public_path('storage/' . auth()->user()->business->logo_path) }}" alt="Logo" style="height: 60px; margin-bottom: 10px;">
        @endif
        <h2>{{ auth()->user()->business->name ?? 'Your Company Name' }}</h2>
        <p>{{ auth()->user()->business->address ?? 'Company Address' }}</p>
        <p>Phone: {{ auth()->user()->business->phone ?? 'Company Phone' }}</p>
        <p>Email: {{ auth()->user()->business->email ?? 'Company Email' }}</p>
        @if(auth()->user()->business->gstin ?? false)
            <p>GSTIN: {{ auth()->user()->business->gstin }}</p>
        @endif
        @if(auth()->user()->business->pan ?? false)
            <p>PAN: {{ auth()->user()->business->pan }}</p>
        @endif
    </div>

    <div class="row">
        <div class="col invoice-details">
            <h3>Invoice Details</h3>
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
            @if($invoice->due_date)
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
            @endif
            @if($invoice->workOrder)
                <p><strong>Work Order:</strong> {{ $invoice->workOrder->order_number }}</p>
            @endif
        </div>

        <div class="col customer-details">
            <h3>Bill To</h3>
            <p><strong>{{ $invoice->customer_name }}</strong></p>
            @if($invoice->customer_email)
                <p>{{ $invoice->customer_email }}</p>
            @endif
            @if($invoice->customer_phone)
                <p>{{ $invoice->customer_phone }}</p>
            @endif
            @if($invoice->customer_gstin)
                <p>GSTIN: {{ $invoice->customer_gstin }}</p>
            @endif
            <p>{{ $invoice->customer_address }}</p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>HSN/SAC</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Tax Rate</th>
                <th>Tax Amount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->material->hsn_code ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                    <td>{{ $item->tax_rate }}%</td>
                    <td>₹{{ number_format($item->tax_amount, 2) }}</td>
                    <td>₹{{ number_format($item->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Subtotal: ₹{{ number_format($invoice->subtotal, 2) }}</p>
        <p>Tax Amount: ₹{{ number_format($invoice->tax_amount, 2) }}</p>
        <p class="total-row">Total Amount: ₹{{ number_format($invoice->total_amount, 2) }}</p>
    </div>

    @if($invoice->notes || auth()->user()->business->terms_and_conditions)
        <div style="margin-top: 20px;">
            @if($invoice->notes)
                <h4>Notes:</h4>
                <p>{{ $invoice->notes }}</p>
            @endif
            
            @if(auth()->user()->business->terms_and_conditions)
                <h4>Terms & Conditions:</h4>
                <p>{{ auth()->user()->business->terms_and_conditions }}</p>
            @endif
        </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>