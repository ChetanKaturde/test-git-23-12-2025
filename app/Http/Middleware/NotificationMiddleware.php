<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
class NotificationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            // Temporarily disable notification count to fix vendor creation
            View::share('unreadNotificationCount', 0);
            View::share('pendingMaterials', collect());
        }

        return $next($request);
    }
}
