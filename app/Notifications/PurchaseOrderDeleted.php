<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderDeleted extends Notification
{
    use Queueable;

    protected $purchaseOrder;
    protected $deletedBy;

    public function __construct(PurchaseOrder $purchaseOrder, User $deletedBy)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->deletedBy = $deletedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Purchase Order Deleted',
            'message' => "PO #{$this->purchaseOrder->po_number} was deleted by {$this->deletedBy->name}.",
            'type' => 'purchase_order_deleted',
            'purchase_order_id' => $this->purchaseOrder->id,
            'purchase_order_number' => $this->purchaseOrder->po_number, // ✅ used in Blade
            'deleted_by' => $this->deletedBy->name,
        ];
    }
}
