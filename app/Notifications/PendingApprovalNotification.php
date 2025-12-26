<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PendingApprovalNotification extends Notification
{
    use Queueable;

    protected $purchaseOrderId;

    public function __construct($purchaseOrderId)
    {
        $this->purchaseOrderId = $purchaseOrderId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $purchaseOrder = PurchaseOrder::find($this->purchaseOrderId);

        return [
            'title' => 'New Purchase Order Pending Approval',
            'message' => "PO #{$purchaseOrder->po_number} is waiting for your approval.",
            'url' => route('purchase-orders.show', $purchaseOrder->id), // ✅ safer
        ];
    }
}
