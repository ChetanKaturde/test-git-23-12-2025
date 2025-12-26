<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;

class CleanupNotifications extends Command
{
    protected $signature = 'notifications:cleanup {--days=30}';
    protected $description = 'Clean up old read notifications';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $deleted = Notification::where('read_at', '<', $cutoffDate)
            ->where('type', 'dashboard')
            ->delete();

        $this->info("Cleaned up {$deleted} old notifications older than {$days} days.");
    }
}

// 10. Event Listener for Purchase Order Events
// Create this file: app/Listeners/PurchaseOrderEventListener.php

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