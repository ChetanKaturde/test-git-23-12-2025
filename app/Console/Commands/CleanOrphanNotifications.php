<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\PurchaseOrder;

class CleanOrphanNotifications extends Command
{
    protected $signature = 'notifications:clean-orphans';
    protected $description = 'Delete orphaned dashboard notifications';

    public function handle()
    {
        $notifications = Notification::where('type', '=', 'dashboard')->get();
        $deletedCount = 0;

        foreach ($notifications as $notification) {
            $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
            if (isset($data['purchase_order_id']) &&
                !PurchaseOrder::withTrashed()->where('id', '=', $data['purchase_order_id'])->exists()) {
                $notification->delete();
                $deletedCount++;
            }
        }

        $this->info("Deleted {$deletedCount} orphaned notifications.");
    }
}
