<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // Cleanup notifications for the logged-in user
    protected function cleanupDeletedOrderNotifications()
    {
        return self::cleanupDeletedOrderNotificationsFor(Auth::id());
    }

    // Admin-only index view with eager loading of purchase orders
   public function index()
{
    if (!Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized. Only administrators can view purchase order notifications.');
    }

    $this->cleanupDeletedOrderNotifications();

    $notifications = DatabaseNotification::where('notifiable_id', Auth::id())
        ->where('notifiable_type', User::class)
        ->where('type', 'dashboard')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    $poIds = $notifications->pluck('data.purchase_order_id')
        ->filter()
        ->unique()
        ->toArray();

    // We no longer use withTrashed here
    $purchaseOrders = PurchaseOrder::whereIn('id', $poIds)->get()->keyBy('id');

    foreach ($notifications as $notification) {
        $poId = $notification->data['purchase_order_id'] ?? null;
        $notification->purchase_order_exists = $poId && $purchaseOrders->has($poId);
        $notification->purchase_order = $purchaseOrders->get($poId) ?? null;
    }

    return view('notifications.index', compact('notifications'));
}


    // Manual cleanup endpoint for admins
    public function manualCleanup()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $deletedCount = $this->cleanupDeletedOrderNotifications();

        return response()->json([
            'success' => true,
            'message' => "Cleanup completed. Deleted {$deletedCount} notifications for deleted purchase orders."
        ]);
    }

    // Static method to clean up deleted order notifications for any user
    public static function cleanupDeletedOrderNotificationsFor($userId)
    {
        $notifications = DatabaseNotification::where('type', 'dashboard')
            ->where('notifiable_id', $userId)
            ->whereRaw("JSON_EXTRACT(data, '$.purchase_order_id') IS NOT NULL")
            ->get();

        $poIds = $notifications->pluck('data.purchase_order_id')->filter()->unique();

        $existingPoIds = PurchaseOrder::withTrashed()->whereIn('id', $poIds)->pluck('id');

        $toDelete = $notifications->filter(function ($notification) use ($existingPoIds) {
            $poId = $notification->data['purchase_order_id'] ?? null;
            return $poId && !$existingPoIds->contains($poId);
        });

        $count = $toDelete->count();

        DatabaseNotification::destroy($toDelete->pluck('id'));

        return $count;
    }

    // Get unread count (for admin polling)
    public function getUnreadCount()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['count' => 0]);
        }

        $this->cleanupDeletedOrderNotifications();

        $count = DatabaseNotification::where('notifiable_id', Auth::id())
            ->where('type', 'dashboard')
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    // Get total unread notification count (non-admin AJAX polling)
    public function getCount()
    {
        try {
            $user = Auth::user();
            $count = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting notification count for user {$user->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    // Mark a specific notification as read
  public function markAsRead($id)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        /** @var \Illuminate\Notifications\DatabaseNotification|null $notification */
        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            Log::warning("Notification not found: {$id} for user: {$user->id}");
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.'
            ], 404);
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        Log::info("Notification marked as read: {$id} by user: {$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    } catch (\Throwable $e) {
        Log::error("Error marking notification {$id} as read: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error marking notification as read.'
        ], 500);
    }
}
public function markAllAsRead()
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        Log::info("All notifications marked as read for user {$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    } catch (\Throwable $e) {
        Log::error("Error marking all notifications as read for user {$user->id}: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error marking all notifications as read.'
        ], 500);
    }
}


  public function dismissAll()
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $notifications = $user->notifications;

        if ($notifications->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No notifications to delete.'
            ]);
        }

        // Delete all notifications
        foreach ($notifications as $notification) {
            $notification->delete();
        }

        \Log::info("All notifications dismissed by user {$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'All notifications dismissed successfully.'
        ]);

    } catch (\Throwable $e) {
        \Log::error("Error dismissing all notifications for user {$user->id}: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error while dismissing all notifications.'
        ], 500);
    }
}

  
    // Dismiss (delete) a specific notification
    public function dismiss($id)
    {
        try {
            $user = Auth::user();

            $notification = $user->notifications()->where('id', $id)->first();

            if (!$notification) {
                Log::warning("Notification not found: {$id} for user: {$user->id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.'
                ], 404);
            }

            $notification->delete();

            Log::info("Notification dismissed: {$id} by user: {$user->id}");

            return response()->json([
                'success' => true,
                'message' => 'Notification dismissed successfully.'
            ]);
        } catch (\Throwable $e) {
            Log::error("Error dismissing notification {$id} for user {$user->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error while dismissing notification.'
            ], 500);
        }
    }

    // Debug method to inspect notification structure
    public function debugNotificationStructure()
    {
        $notifications = DatabaseNotification::where('type', 'dashboard')->limit(5)->get();

        foreach ($notifications as $notification) {
            Log::info('Notification Debug', [
                'id' => $notification->id,
                'data_type' => gettype($notification->data),
                'data_content' => $notification->data,
                'data_decoded' => is_string($notification->data) ? json_decode($notification->data, true) : $notification->data
            ]);
        }

        return response()->json(['message' => 'Check logs for notification structure']);
    }
}
