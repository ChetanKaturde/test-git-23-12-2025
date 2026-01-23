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
        .section { margin: 20px 0; }
        .section h3 { border-bottom: 1px solid #ddd; padding-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            @if($business->logo_path && file_exists(public_path($business->logo_path)))
                <img src="{{ url($business->logo_path) }}" alt="Logo" class="logo">
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
            @if($business->pan)<p>PAN: {{ $business->pan }}</p>@endif
        </div>
    </div>

    @if($is_preview)
    <div class="section">
        <h3>Business Profile Preview</h3>
        <p>This is how your business information will appear on quotations and invoices.</p>
        @if($business->terms_and_conditions)
        <div style="margin-top: 20px;">
            <h4>Terms & Conditions:</h4>
            <p>{{ $business->terms_and_conditions }}</p>
        </div>
        @endif
    </div>
    @endif

    <div style="margin-top: 40px; text-align: center; color: #666; font-size: 12px;">
        <p>Generated on {{ now()->format('d M Y') }}</p>
    </div>
</body>
</html>