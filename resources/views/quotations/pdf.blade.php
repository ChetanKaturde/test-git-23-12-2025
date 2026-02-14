<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info { float: left; width: 50%; }
        .quotation-info { float: right; width: 45%; text-align: right; }
        .customer-info { margin: 30px 0; padding: 15px; background: #f9f9f9; }
        .items-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background: #f5f5f5; font-weight: bold; }
        .totals { float: right; width: 300px; margin-top: 20px; }
        .totals table { width: 100%; }
        .totals td { padding: 8px; border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; background: #f0f0f0; }
        .terms { margin-top: 50px; clear: both; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="company-info">
            @if($business->logo_path)
                <img src="{{ public_path('storage/' . $business->logo_path) }}" alt="Logo" style="height: 60px; margin-bottom: 10px;">
            @endif
            <h1>{{ $business->name ?? 'Your Business' }}</h1>
            @if($business->address)
                <p>{{ $business->address }}</p>
            @endif
            @if($business->city || $business->state)
                <p>{{ $business->city }}{{ $business->city && $business->state ? ', ' : '' }}{{ $business->state }} {{ $business->pin_code }}</p>
            @endif
            @if($business->phone)
                <p>Phone: {{ $business->phone }}</p>
            @endif
            @if($business->email)
                <p>Email: {{ $business->email }}</p>
            @endif
            @if($business->gstin)
                <p>GSTIN: {{ $business->gstin }}</p>
            @endif
            @if($business->pan)
                <p>PAN: {{ $business->pan }}</p>
            @endif
        </div>
        
        <div class="quotation-info">
            <h2>QUOTATION</h2>
            <p><strong>Number:</strong> {{ $quotation->number }}</p>
            <p><strong>Date:</strong> {{ $quotation->created_at->format('d/m/Y') }}</p>
            <p><strong>Valid Until:</strong> {{ $quotation->valid_until->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="customer-info">
        <h3>Bill To:</h3>
        <p><strong>{{ $quotation->customer->name }}</strong></p>
        @if($quotation->customer->address)
            <p>{{ $quotation->customer->address }}</p>
        @endif
        @if($quotation->customer->city || $quotation->customer->state)
            <p>{{ $quotation->customer->city }}{{ $quotation->customer->city && $quotation->customer->state ? ', ' : '' }}{{ $quotation->customer->state }}</p>
        @endif
        @if($quotation->customer->pincode)
            <p>PIN: {{ $quotation->customer->pincode }}</p>
        @endif
        @if($quotation->customer->phone)
            <p>Phone: {{ $quotation->customer->phone }}</p>
        @endif
        @if($quotation->customer->email)
            <p>Email: {{ $quotation->customer->email }}</p>
        @endif
        @if($quotation->customer->gstin)
            <p>GSTIN: {{ $quotation->customer->gstin }}</p>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>HSN/SAC</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Tax %</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->material->hsn_code ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ $item->tax_rate }}%</td>
                    <td>₹{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>₹{{ number_format($quotation->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td>₹{{ number_format($quotation->tax_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td><strong>₹{{ number_format($quotation->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    @if($quotation->notes || $business->terms_and_conditions)
        <div class="terms">
            @if($quotation->notes)
                <h4>Notes:</h4>
                <p>{{ $quotation->notes }}</p>
            @endif
            
            @if($business->terms_and_conditions)
                <h4>Terms & Conditions:</h4>
                <p>{{ $business->terms_and_conditions }}</p>
            @endif
        </div>
    @endif

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>