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
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Map module and permission to JSON permission key
        $permissionKey = $this->mapPermission($moduleName, $permission);

        if (!$user->hasPermission($permissionKey)) {
            Log::warning('Permission denied', [
                'user_id' => $user->id,
                'module' => $moduleName,
                'permission' => $permission,
                'permission_key' => $permissionKey
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Insufficient permissions'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', "You don't have permission to {$permission} in the {$moduleName} module.");
        }

        return $next($request);
    }

    private function mapPermission($moduleName, $permission)
    {
        $mappings = [
            'quotations' => [
                'view' => 'view_quotation',
                'create' => 'create_quotation',
                'edit' => 'edit_quotation',
                'delete' => 'delete_quotation'
            ],
            'materials' => [
                'view' => 'view_material',
                'create' => 'create_material',
                'edit' => 'edit_material',
                'delete' => 'delete_material'
            ],
            'vendors' => [
                'view' => 'view_vendor',
                'create' => 'create_vendor',
                'edit' => 'edit_vendor',
                'delete' => 'delete_vendor'
            ],
            'purchase_orders' => [
                'view' => 'view_purchase_order',
                'create' => 'create_purchase_order',
                'edit' => 'edit_purchase_order',
                'delete' => 'delete_purchase_order'
            ],
            'machines' => [
                'view' => 'view_machine',
                'create' => 'create_machine',
                'edit' => 'edit_machine',
                'delete' => 'delete_machine'
            ],
            'work_orders' => [
                'view' => 'view_work_order',
                'create' => 'create_work_order',
                'edit' => 'edit_work_order',
                'delete' => 'delete_work_order'
            ],
            'inventory' => [
                'view' => 'view_inventory',
                'create' => 'create_inventory',
                'edit' => 'edit_inventory',
                'delete' => 'delete_inventory'
            ],
            'reports' => [
                'view' => 'view_report'
            ]
        ];

        return $mappings[$moduleName][$permission] ?? "{$permission}_{$moduleName}";
    }
}