<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateHttpRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🔐 Block a specific IP address
        if ($request->ip() === '152.59.63.10') {
            \Log::warning('Blocked request from blacklisted IP', [
                'ip' => $request->ip(),
                'uri' => $request->getRequestUri(),
                'user_agent' => $request->userAgent()
            ]);

            return response('Access denied', 403);
        }

        // ❌ Block malformed URI like "/login 2.0"
        if (preg_match('/\s2\.0$/', $request->getRequestUri())) {
            \Log::warning('Blocked malformed request URI', [
                'uri' => $request->getRequestUri(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json(['error' => 'Malformed request'], 400);
        }

        return $next($request);
    }
}
