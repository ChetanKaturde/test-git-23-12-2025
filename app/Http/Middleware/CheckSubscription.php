<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->business_id) {
            return $next($request);
        }

        // Check if business has active subscription
        $activeSubscription = Subscription::where('business_id', $user->business_id)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();

        if (!$activeSubscription) {
            // No active subscription
            if ($user->role === 'admin') {
                // Allow admin to access pricing/renewal pages
                if ($request->routeIs('pricing', 'subscription.payment', 'subscription.payment.process')) {
                    return $next($request);
                }
                // Redirect admin to pricing for renewal
                return redirect()->route('pricing')->with('warning', 'Your subscription has expired. Please renew to continue using all features.');
            } else {
                // Block non-admin users
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your subscription has expired. Please contact your administrator to renew.');
            }
        }

        return $next($request);
    }
}
