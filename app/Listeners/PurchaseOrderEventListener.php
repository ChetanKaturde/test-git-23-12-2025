<?php

namespace App\Listeners;

use App\Events\PurchaseOrderStatusChanged;
use App\Services\NotificationService;

class PurchaseOrderEventListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(PurchaseOrderStatusChanged $event)
    {
        $this->notificationService->notifyStatusChange(
            $event->purchaseOrder,
            $event->oldStatus,
            $event->newStatus
        );
    }
}
