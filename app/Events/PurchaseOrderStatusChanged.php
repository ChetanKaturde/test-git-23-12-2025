<?php

namespace App\Events;

use App\Models\PurchaseOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchaseOrder;
    public $oldStatus;
    public $newStatus;

    public function __construct(PurchaseOrder $purchaseOrder, $oldStatus, $newStatus)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
