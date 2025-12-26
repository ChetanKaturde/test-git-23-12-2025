<?php

// app/Http/Middleware/VerifyCsrfToken.php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     */
    protected $except = [
        // Add any routes that should be excluded from CSRF verification
        // Example: 'api/*', 'webhooks/*'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        // Add custom CSRF handling logic if needed
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Log the CSRF token mismatch
            \Log::warning('CSRF Token Mismatch', [
                'url' => $request->url(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_token' => $request->session()->token(),
                'input_token' => $request->input('_token'),
                'header_token' => $request->header('X-CSRF-TOKEN'),
            ]);
            
            // For AJAX requests, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'CSRF token mismatch. Please refresh the page and try again.',
                    'code' => 419
                ], 419);
            }
            
            // For regular requests, redirect back with error
            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Security token expired. Please try again.');
        }
    }
}