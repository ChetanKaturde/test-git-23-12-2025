<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PurchaseOrderCreated extends Notification
{
    protected $purchaseOrderId;
    protected $creatorName;

    public function __construct($purchaseOrderId, $creatorName)
    {
        $this->purchaseOrderId = $purchaseOrderId;
        $this->creatorName = $creatorName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
{
    $purchaseOrder = PurchaseOrder::find($this->purchaseOrderId);

    return [
        'title' => "PO #{$purchaseOrder->po_number} Created",
        'message' => "PO #{$purchaseOrder->po_number} was created by {$this->creatorName}. Status: " . ucfirst($purchaseOrder->status) . ".",
        'url' => route('purchase-orders.show', $purchaseOrder->id),
        'approve_url' => route('purchase-orders.approve', $purchaseOrder->id),
        'order_id' => $purchaseOrder->id,
    ];
}

}
