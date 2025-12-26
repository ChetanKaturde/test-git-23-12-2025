<?php

namespace App\Observers;

use App\Models\PurchaseOrder;
use App\Services\NotificationService;

class PurchaseOrderObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(PurchaseOrder $purchaseOrder)
    {
        // Trigger notification when PO is created with pending status
        if ($purchaseOrder->status === 'pending') {
            $this->notificationService->notifyPendingApproval($purchaseOrder);
        }
    }

    public function updated(PurchaseOrder $purchaseOrder)
    {
        // Trigger notification when status changes to pending
        if ($purchaseOrder->isDirty('status') && $purchaseOrder->status === 'pending') {
            $this->notificationService->notifyPendingApproval($purchaseOrder);
        }
    }
}
