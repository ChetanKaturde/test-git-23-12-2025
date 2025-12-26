<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Module;
use App\Models\Business;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, $moduleName, $permission = 'view')
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $business = $user->business;
        
        // Check subscription tier restrictions
        if ($business && $business->subscription_tier === 'billing_sales') {
            $allowedModules = ['materials', 'customers', 'quotations', 'invoices', 'reports', 'team'];
            
            if (!in_array($moduleName, $allowedModules)) {
                Log::warning('Module blocked by subscription tier', [
                    'user_id' => $user->id,
                    'module' => $moduleName,
                    'tier' => 'billing_sales'
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Module not available in your subscription plan'], 403);
                }
                
                return redirect()->route('dashboard')
                    ->with('error', "The {$moduleName} module is not available in your subscription plan.");
            }
        }
        
        // Admin has full access
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Find module by name with proper validation
        $module = Module::where('name', $moduleName)->where('is_active', true)->first();
        
        if (!$module) {
            Log::error('Module not found', ['module' => $moduleName]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Module not available'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', "The {$moduleName} module is not available.");
        }
        
        if (!$user->hasModulePermission($module->id, $permission)) {
            Log::warning('Permission denied', [
                'user_id' => $user->id,
                'module' => $moduleName,
                'permission' => $permission
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Insufficient permissions'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', "You don't have permission to {$permission} in the {$moduleName} module.");
        }

        return $next($request);
    }
}