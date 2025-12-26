<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseOrderApproved extends Notification
{
    use Queueable;

    protected $purchaseOrderId;  // Store only ID

    public function __construct($purchaseOrderId)
    {
        $this->purchaseOrderId = $purchaseOrderId;
    }

    public function via($notifiable)
    {
        return ['database'];  // Dashboard notification
    }

    public function toDatabase($notifiable)
    {
        $purchaseOrder = PurchaseOrder::find($this->purchaseOrderId);

        return [
            'title' => 'Purchase Order Approved',
            'message' => "PO #{$purchaseOrder->po_number} has been approved.",
            'url' => route('purchase-orders.show', $purchaseOrder->id), // <-- safer and correct
        ];
    }
}
