<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->business_id) {
            return $next($request);
        }

        // Routes allowed when subscription expired (for owners)
        $allowedRoutes = [
            'logout',
            'login',
            'pricing',
            'pricing.process',
            'subscription.payment',
            'subscription.payment.process'
        ];

        if ($request->routeIs($allowedRoutes)) {
            return $next($request);
        }

        $activeSubscription = Subscription::where('business_id', $user->business_id)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        if (!$activeSubscription) {
            if ($user->role === 'admin') {
                return redirect()->route('pricing')->with('warning', 'Your subscription has expired. Please renew to continue.');
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Subscription expired. Contact your administrator.');
            }
        }

        return $next($request);
    }
}
