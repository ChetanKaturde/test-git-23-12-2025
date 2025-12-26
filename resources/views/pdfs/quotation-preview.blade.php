<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Profile Preview</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logo { width: 80px; height: 80px; }
        .business-info { text-align: right; }
        .customer-info { margin: 20px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f5f5f5; }
        .totals { text-align: right; margin: 20px 0; }
        .terms { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            @if($business->logo_path && file_exists(public_path('storage/' . $business->logo_path)))
                <img src="{{ public_path('storage/' . $business->logo_path) }}" alt="Logo" class="logo">
            @else
                <div style="width: 80px; height: 80px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; color: #374151;">
                    {{ substr($business->name ?? 'B', 0, 1) }}
                </div>
            @endif
        </div>
        <div class="business-info">
            <h2>{{ $business->name }}</h2>
            @if($business->legal_name && $business->legal_name !== $business->name)
                <p><strong>Legal Name:</strong> {{ $business->legal_name }}</p>
            @endif
            <p>{{ $business->address }}</p>
            <p>{{ $business->city }}, {{ $business->state }} - {{ $business->pin_code }}</p>
            @if($business->phone)<p>Phone: {{ $business->phone }}</p>@endif
            @if($business->email)<p>Email: {{ $business->email }}</p>@endif
            @if($business->gstin)<p>GSTIN: {{ $business->gstin }}</p>@endif
        </div>
    </div>

    <h3>QUOTATION</h3>
    <p><strong>Quotation No:</strong> {{ $quotation->quotation_number }}</p>
    <p><strong>Date:</strong> {{ $quotation->issue_date }}</p>
    <p><strong>Valid Until:</strong> {{ $quotation->valid_until }}</p>

    <div class="customer-info">
        <h4>Bill To:</h4>
        <p><strong>{{ $customer->name }}</strong></p>
        <p>{{ $customer->address }}</p>
        <p>Phone: {{ $customer->phone }}</p>
        <p>Email: {{ $customer->email }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>HSN/SAC</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->hsn_code }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $business->currency === 'INR' ? '₹' : '$' }}{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $business->currency === 'INR' ? '₹' : '$' }}{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> {{ $business->currency === 'INR' ? '₹' : '$' }}{{ number_format($quotation->subtotal, 2) }}</p>
        <p><strong>Tax (18%):</strong> {{ $business->currency === 'INR' ? '₹' : '$' }}{{ number_format($quotation->tax_amount, 2) }}</p>
        <p><strong>Total:</strong> {{ $business->currency === 'INR' ? '₹' : '$' }}{{ number_format($quotation->total_amount, 2) }}</p>
    </div>

    @if($business->terms_and_conditions)
    <div class="terms">
        <h4>Terms & Conditions:</h4>
        <p>{{ $business->terms_and_conditions }}</p>
    </div>
    @endif

    <div style="margin-top: 40px; text-align: center; color: #666; font-size: 12px;">
        <p>This is a preview of how your business information will appear on quotations and invoices.</p>
    </div>
</body>
</html>