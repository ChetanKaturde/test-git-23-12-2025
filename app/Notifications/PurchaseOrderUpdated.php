<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderUpdated extends Notification
{
    use Queueable;

    protected $purchaseOrder;
    protected $updatedBy;
    protected $changes;

    public function __construct(PurchaseOrder $purchaseOrder, User $updatedBy, array $changes)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->updatedBy = $updatedBy;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Purchase Order Updated',
            'message' => "PO #{$this->purchaseOrder->po_number} was updated by {$this->updatedBy->name}.",
            'type' => 'purchase_order_updated',
            'purchase_order_id' => $this->purchaseOrder->id,
            'purchase_order_number' => $this->purchaseOrder->po_number,
            'updated_by' => $this->updatedBy->name,
            'changes' => $this->changes,
        ];
    }
}
