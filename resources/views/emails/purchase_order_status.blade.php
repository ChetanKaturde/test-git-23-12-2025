
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ ucfirst($status) }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { padding: 20px; text-align: center; border-radius: 5px; }
        .header.approved { background: #d4edda; color: #155724; }
        .header.rejected { background: #f8d7da; color: #721c24; }
        .header.completed { background: #d1ecf1; color: #0c5460; }
        .content { padding: 20px; background: #f8f9fa; margin: 20px 0; }
        .po-details { background: white; padding: 15px; border-radius: 5px; }
        .button { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $status }}">
            <h1>Purchase Order {{ ucfirst($status) }}</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            <p>Your purchase order has been <strong>{{ $status }}</strong>:</p>
            
            <div class="po-details">
                <h3>Purchase Order Details</h3>
                <p><strong>PO Number:</strong> {{ $po->po_number }}</p>
                <p><strong>Vendor:</strong> {{ $po->vendor->name }}</p>
                <p><strong>Date:</strong> {{ $po->po_date->format('d M Y') }}</p>
                <p><strong>Total Amount:</strong> ₹{{ number_format($po->final_amount, 2) }}</p>
                
                @if($po->approval_notes)
                <p><strong>{{ $status === 'rejected' ? 'Rejection Reason' : 'Approval Notes' }}:</strong> 
                   {{ $po->approval_notes }}</p>
                @endif
                
                @if($po->approvedBy)
                <p><strong>{{ ucfirst($status) }} By:</strong> {{ $po->approvedBy->name }}</p>
                <p><strong>{{ ucfirst($status) }} At:</strong> {{ $po->approved_at->format('d M Y, h:i A') }}</p>
                @endif
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('purchase-orders.show', $po) }}" class="button">View Purchase Order</a>
            </div>
            
            @if($status === 'approved')
            <p>Your purchase order is now ready for processing. You will receive further updates as the order progresses.</p>
            @elseif($status === 'rejected')
            <p>Please review the rejection reason and make necessary changes before resubmitting.</p>
            @endif
        </div>
    </div>
</body>
</html>
