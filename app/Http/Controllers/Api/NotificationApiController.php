<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Auth::user()->notifications()
            ->where('type', 'dashboard')
            ->when($request->unread_only, function($query) {
                $query->whereNull('read_at');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Auth::user()->notifications()
            ->where('type', '=', 'dashboard')
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}

?>