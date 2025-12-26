<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchaseOrder;
    public $status;

    public function __construct(PurchaseOrder $purchaseOrder, $status)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->status = $status;
    }

    public function build()
    {
        $subjects = [
            'approved' => 'Purchase Order Approved',
            'rejected' => 'Purchase Order Rejected',
            'cancelled' => 'Purchase Order Cancelled'
        ];

        return $this->subject($subjects[$this->status] . " - {$this->purchaseOrder->po_number}")
                    ->view('emails.purchase_order_status')
                    ->with([
                        'po' => $this->purchaseOrder,
                        'status' => $this->status
                    ]);
    }
}