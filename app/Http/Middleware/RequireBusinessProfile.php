<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireBusinessProfile
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $next($request);
        }
        
        $business = $user->business;
        
        // Skip profile completion check for certain routes
        $exemptRoutes = [
            'business.profile',
            'business.profile.update',
            'business.load-sample-data',
            'logout',
            'business.profile.preview'
        ];
        
        if (in_array($request->route()->getName(), $exemptRoutes)) {
            return $next($request);
        }
        
        // Check if profile is complete
        $isProfileComplete = !empty($business->name) && 
                           !empty($business->address) && 
                           (!empty($business->gstin) || !empty($business->city));
        
        if (!$isProfileComplete) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please complete your business profile first.',
                    'redirect' => route('business.profile')
                ], 422);
            }
            
            return redirect()->route('business.profile')
                ->with('info', 'Please complete your business profile to continue.');
        }
        
        return $next($request);
    }
}