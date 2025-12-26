<?php

namespace App\Jobs;

use App\Models\PurchaseOrder;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $purchaseOrderId;

    public function __construct($purchaseOrderId)
    {
        $this->purchaseOrderId = $purchaseOrderId;
    }

    public function handle(NotificationService $notificationService)
    {
        $purchaseOrder = PurchaseOrder::with('vendor')->find($this->purchaseOrderId);
        
        if ($purchaseOrder && $purchaseOrder->status === 'pending') {
            // IMPORTANT: disable async to avoid infinite recursion
            $notificationService->notifyPendingApproval($purchaseOrder, false);
        }
    }
}
