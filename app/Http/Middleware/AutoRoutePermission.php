<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutoRoutePermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Allow admin and super_admin
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return $next($request);
        }
        
        $currentRoute = $request->route()->getName();
        $uri = $request->path();
        
        // Route pattern detection
        $modulePatterns = [
            'materials' => 4,
            'vendors' => 5,
            'warehouses' => 2,
            'users' => 1,
            'blocks' => 3,
            'quality-analysis' => 6,
            'purchase-orders' => 7,
            'inventory' => 8,
            'barcode' => 9,
            'reports' => 10,
        ];
        
        foreach ($modulePatterns as $pattern => $moduleId) {
            if (strpos($uri, $pattern) !== false || strpos($currentRoute, $pattern) !== false) {
                
                $permission = 'can_view'; // default
                
                // Detect action type
                if (strpos($uri, '/create') !== false || strpos($currentRoute, 'create') !== false) {
                    $permission = 'can_create';
                } elseif (strpos($uri, '/edit') !== false || strpos($currentRoute, 'edit') !== false) {
                    $permission = 'can_edit';
                } elseif ($request->method() === 'DELETE' || strpos($currentRoute, 'destroy') !== false) {
                    $permission = 'can_delete';
                }
                
                // Check permission
                $hasPermission = DB::table('permissions')
                    ->where('user_id', $user->id)
                    ->where('module_id', $moduleId)
                    ->where($permission, 1)
                    ->exists();
                
                if (!$hasPermission) {
                    return redirect()->back()->with('error', 'You do not have permission to access this page.');
                }
                
                break;
            }
        }
        
        return $next($request);
    }
}
