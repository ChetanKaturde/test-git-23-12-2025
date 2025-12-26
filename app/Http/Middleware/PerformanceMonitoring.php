<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerformanceMonitoring
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $executionTime = round(($endTime - $startTime) * 1000, 2); // milliseconds
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2); // MB

        // Log slow requests (>1000ms)
        if ($executionTime > 1000) {
            Log::warning('Slow Request Detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime . 'ms',
                'memory_usage' => $memoryUsage . 'MB',
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
        }

        // Add performance headers for debugging
        if (config('app.debug')) {
            $response->headers->set('X-Execution-Time', $executionTime . 'ms');
            $response->headers->set('X-Memory-Usage', $memoryUsage . 'MB');
        }

        return $response;
    }
}