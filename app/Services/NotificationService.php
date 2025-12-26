<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Mail\PurchaseOrderApprovalMail;
use App\Mail\PurchaseOrderStatusMail;
use App\Notifications\PurchaseOrderApproved;
use App\Notifications\LowStockAlert;
use App\Jobs\SendApprovalNotification;
use Illuminate\Support\Str;


class NotificationService
{
    public static function notifyInventoryTeam(PurchaseOrder $purchaseOrder)
    {
        $inventoryUsers = User::where('role', 'inventory_manager')->get();
        Notification::send($inventoryUsers, new PurchaseOrderApproved($purchaseOrder->id));
    }

    public static function sendLowStockAlert(string $material, int $currentStock)
    {
        $managers = User::where('role', 'admin')->get();
        Notification::send($managers, new LowStockAlert($material, $currentStock));
    }

    public function notifyPendingApproval(PurchaseOrder $purchaseOrder, $async = true)
    {
        if ($async) {
            SendApprovalNotification::dispatch($purchaseOrder->id);
            return;
        }

        $approvers = $this->getApprovers($purchaseOrder);

        foreach ($approvers as $approver) {
            $preferences = $approver->getNotificationPreferences();

            if (!empty($preferences['email'])) {
                $this->sendEmailNotification($approver, $purchaseOrder);
            }

            if (!empty($preferences['sms'])) {
                $this->sendSmsNotification($approver, $purchaseOrder);
            }

            if (!empty($preferences['dashboard'])) {
                $this->createDashboardNotification($approver, $purchaseOrder);
            }
        }
    }

    private function getApprovers(PurchaseOrder $purchaseOrder)
    {
        $amount = $purchaseOrder->final_amount;

        if ($amount > 100000) {
            return User::where('role', 'admin')->get();
        } elseif ($amount > 50000) {
            return User::whereIn('role', ['admin', 'purchase_team'])->get();
        } else {
            return User::whereIn('role', ['admin', 'purchase_team', 'inventory_manager'])->get();
        }
    }

    private function sendEmailNotification(User $user, PurchaseOrder $purchaseOrder)
    {
        try {
            Mail::to($user->email)->send(new PurchaseOrderApprovalMail($purchaseOrder));
            Log::info("Approval email sent to {$user->email} for PO {$purchaseOrder->po_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
        }
    }

private function sendSmsNotification(User $user, PurchaseOrder $purchaseOrder)
{
    try {
        if (empty($user->phone)) {
            Log::warning("No phone number for user {$user->id}, skipping SMS.");
            return;  // Skip if no phone number
        }

        $message = "PO {$purchaseOrder->po_number} (₹" . number_format($purchaseOrder->final_amount, 0) . ") needs approval. Login to review.";

        if (config('services.twilio.sid')) {
            $this->sendTwilioSms($user->phone, $message);
        } else {
            $this->sendGenericSms($user->phone, $message);
        }

        Log::info("SMS notification sent to {$user->phone} for PO {$purchaseOrder->po_number}");
    } catch (\Exception $e) {
        Log::error("Failed to send SMS notification: " . $e->getMessage());
    }
}


    private function sendTwilioSms($phone, $message)
    {
        $twilio = new \Twilio\Rest\Client(
            config('services.twilio.sid'), 
            config('services.twilio.token')
        );

        $twilio->messages->create($phone, [
            'from' => config('services.twilio.from'),
            'body' => $message
        ]);
    }

    private function sendGenericSms($phone, $message)
    {
        $response = Http::post(config('services.sms.base_url'), [
            'apikey' => config('services.sms.api_key'),
            'numbers' => $phone,
            'message' => $message,
            'sender' => config('services.sms.sender_id')
        ]);

        if (!$response->successful()) {
            throw new \Exception('SMS API request failed');
        }
    }

private function createDashboardNotification(User $user, PurchaseOrder $purchaseOrder)
{
    $priority = $this->calculatePriority($purchaseOrder);

    $user->notifications()->create([
        'id' => (string) Str::uuid(), // ✅ Manually generate UUID
        'type' => 'dashboard',
        'data' => [
            'title' => 'Purchase Order Approval Required',
            'message' => "PO #{$purchaseOrder->po_number} from {$purchaseOrder->vendor->name} requires your approval",
            'amount' => $purchaseOrder->final_amount,
            'vendor' => $purchaseOrder->vendor->name,
            'po_date' => $purchaseOrder->po_date->format('d M Y'),
            'url' => route('purchase-orders.show', $purchaseOrder->id),
            'priority' => $priority,
            'category' => 'approval_required'
        ]
    ]);
}


    private function calculatePriority(PurchaseOrder $purchaseOrder)
    {
        $amount = $purchaseOrder->final_amount;
        $daysSinceCreated = $purchaseOrder->created_at->diffInDays(now());

        if ($amount > 100000 || $daysSinceCreated > 2) {
            return 'high';
        } elseif ($amount > 50000 || $daysSinceCreated > 1) {
            return 'medium';
        }
        return 'normal';
    }

    public function notifyStatusChange(PurchaseOrder $purchaseOrder, $oldStatus, $newStatus)
    {
        if ($purchaseOrder->created_by) {
            $creator = User::find($purchaseOrder->created_by);
            if ($creator) {
                $this->notifyCreator($creator, $purchaseOrder, $newStatus);
            }
        }
    }

    private function notifyCreator(User $creator, PurchaseOrder $purchaseOrder, $status)
    {
        $titles = [
            'approved'  => 'Purchase Order Approved',
            'rejected'  => 'Purchase Order Rejected',
            'completed' => 'Purchase Order Completed',
            'cancelled' => 'Purchase Order Cancelled',
            'ordered'   => 'Purchase Order Ordered',
            'received'  => 'Purchase Order Received',
            'pending'   => 'Purchase Order Pending',
        ];

        $messages = [
            'approved'  => "Your PO #{$purchaseOrder->po_number} has been approved.",
            'rejected'  => "Your PO #{$purchaseOrder->po_number} has been rejected.",
            'completed' => "Your PO #{$purchaseOrder->po_number} is completed.",
            'cancelled' => "Your PO #{$purchaseOrder->po_number} has been cancelled.",
            'ordered'   => "Your PO #{$purchaseOrder->po_number} has been ordered.",
            'received'  => "Your PO #{$purchaseOrder->po_number} has been received.",
            'pending'   => "Your PO #{$purchaseOrder->po_number} is pending approval.",
        ];

        $creator->notifications()->create([
            'type' => 'dashboard',
            'data' => [
                'title' => $titles[$status] ?? 'Purchase Order Status Updated',
                'message' => $messages[$status] ?? "Your PO #{$purchaseOrder->po_number} status updated to {$status}.",
                'amount' => $purchaseOrder->final_amount,
                'vendor' => $purchaseOrder->vendor->name,
                'url' => route('purchase-orders.show', $purchaseOrder->id),  // <-- FIXED HERE
                'priority' => $this->calculatePriority($purchaseOrder),
                'category' => 'status_update'
            ]
        ]);

        $preferences = $creator->getNotificationPreferences();
        if (!empty($preferences['email'])) {
            Mail::to($creator->email)->send(new PurchaseOrderStatusMail($purchaseOrder, $status));
        }
    }
}
