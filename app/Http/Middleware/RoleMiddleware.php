<?php
// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Usage: 
     * - Single role: 'role:admin'
     * - Multiple roles: 'role:admin,purchase_team,inventory_manager'
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            if (!Auth::check()) {
                return $this->redirectToLogin($request);
            }

            $user = Auth::user();
            
            // Add null check for user
            if (!$user) {
                return $this->redirectToLogin($request);
            }

            // If no roles specified, just check if user is authenticated
            if (empty($roles)) {
                return $next($request);
            }

            // Convert single string with comma-separated roles to array
            if (count($roles) === 1 && str_contains($roles[0], ',')) {
                $roles = explode(',', $roles[0]);
            }

            // Trim whitespace from roles
            $roles = array_map('trim', $roles);

            // Check if user has any of the required roles
            if (!in_array($user->role, $roles)) {
                // Log unauthorized access attempt
                Log::warning('Unauthorized access attempt', [
                    'user_id' => $user->id,
                    'required_roles' => $roles,
                    'user_role' => $user->role,
                    'url' => $request->url(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return $this->handleUnauthorized($request, $roles);
            }

            return $next($request);
            
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }

    /**
     * Handle unauthorized access
     */
    private function handleUnauthorized(Request $request, array $roles)
    {
        $rolesList = implode(', ', array_map(function($role) {
            return ucfirst(str_replace('_', ' ', $role));
        }, $roles));

        // If AJAX request, return JSON error
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Access denied. Required role(s): ' . $rolesList,
                'code' => 403
            ], 403);
        }

        // Redirect to appropriate dashboard with error message
        return redirect()->route('dashboard')
            ->with('error', 'Access denied. This section requires one of the following roles: ' . $rolesList);
    }

    /**
     * Redirect to login
     */
    private function redirectToLogin(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Authentication required',
                'code' => 401
            ], 401);
        }

        return redirect()->route('login')
            ->with('info', 'Please log in to access this section.');
    }

    /**
     * Handle exceptions
     */
    private function handleException(Request $request, \Exception $e)
    {
        // Log the error for debugging
        Log::error('RoleMiddleware error', [
            'error' => $e->getMessage(),
            'url' => $request->url(),
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return appropriate error response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'code' => 500
            ], 500);
        }
        
        return redirect()->route('login')
            ->with('error', 'An error occurred. Please try again.');
    }
  
}
