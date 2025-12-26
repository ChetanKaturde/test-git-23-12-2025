<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function build()
    {
        return $this->subject("Purchase Order Approval Required - {$this->purchaseOrder->po_number}")
                    ->view('emails.purchase_order_approval')
                    ->with([
                        'po' => $this->purchaseOrder,
                        'approvalUrl' => route('purchase-orders.approve', $this->purchaseOrder)
                    ]);
    }
}
