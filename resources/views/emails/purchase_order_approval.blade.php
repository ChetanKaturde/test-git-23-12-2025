<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase Order Approval Required</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .po-details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .button { display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .button.reject { background: #dc3545; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Order Approval Required</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            <p>A new purchase order requires your approval:</p>
            
            <div class="po-details">
                <h3>Purchase Order Details</h3>
                <p><strong>PO Number:</strong> {{ $po->po_number }}</p>
                <p><strong>Vendor:</strong> {{ $po->vendor->name }}</p>
                <p><strong>Date:</strong> {{ $po->po_date->format('d M Y') }}</p>
                <p><strong>Total Amount:</strong> ₹{{ number_format($po->final_amount, 2) }}</p>
                @if($po->notes)
                <p><strong>Notes:</strong> {{ $po->notes }}</p>
                @endif
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('purchase-orders.show', $po) }}" class="button">View Purchase Order</a>
                <a href="{{ route('purchase-orders.approve', $po) }}" class="button">Approve</a>
                <a href="{{ route('purchase-orders.show', $po) }}#reject" class="button reject">Reject</a>
            </div>
            
            <p>Please review and take appropriate action at your earliest convenience.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>