<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\Config;

class SubdomainMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        // Skip for main domain
        if ($subdomain === 'portfolio3' || $subdomain === 'www' || !str_contains($host, '.')) {
            return $next($request);
        }
        
        // Find business by subdomain
        $business = Business::where('slug', $subdomain)->first();
        
        if (!$business) {
            abort(404, 'Business not found');
        }
        
        // Set current business in config
        Config::set('app.current_business', $business);
        
        return $next($request);
    }
}