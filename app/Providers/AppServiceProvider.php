<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\Warehouse;
use App\Models\Module;
use App\Models\PurchaseOrder;
use App\Models\Business;
use App\Observers\PurchaseOrderObserver;
use App\Observers\BusinessObserver;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Force URL generation without port
        URL::forceRootUrl(config('app.url'));
        if (request()->isSecure()) {
            URL::forceScheme('https');
        }
        
        // 1. Register observers
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        Business::observe(BusinessObserver::class);

        // 2. Share all warehouses globally to views
        View::composer('*', function ($view) {
            $view->with('allWarehouses', Warehouse::all());
        });

        // 3. Share sidebar permissions dynamically based on specific permissions
        View::composer('*', function ($view) {
            $user = Auth::user();
            $sidebarPermissions = [];

            if ($user) {
                $sidebarPermissions = $this->getSidebarPermissions($user);
            }

            $view->with('sidebarPermissions', $sidebarPermissions);
        });

        // 4. Share allowed modules based on subscription tier
        View::composer('*', function ($view) {
            $user = auth()->user();
            $allowedModules = [];
            
            if ($user && $user->business) {
                $tier = $user->business->subscription_tier;
                $allowedModules = match ($tier) {
                    'billing_sales' => ['customers', 'quotations', 'invoices', 'team', 'reports'],
                    default => ['materials', 'machines', 'work_orders', 'inventory', 'vendors', 'purchase_orders', 'customers', 'quotations', 'invoices', 'team', 'reports'],
                };
            }
            
            $view->with('allowedModules', $allowedModules);
        });

        // 5. Share permission checking function globally
        \Illuminate\Support\Facades\Blade::if('canAccess', function ($action, $module) {
            $user = auth()->user();

            if (!$user) return false;

            if (in_array($user->role, ['admin', 'super_admin'])) {
                return true;
            }

            $moduleIds = [
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

            $validActions = ['view', 'edit', 'create', 'delete', 'assign'];
            if (!in_array($action, $validActions)) return false;

            $moduleId = $moduleIds[$module] ?? null;
            if (!$moduleId) return false;

            $permissionColumn = 'can_' . $action;

            return \DB::table('permissions')
                ->where('user_id', $user->id)
                ->where('module_id', $moduleId)
                ->where($permissionColumn, 1)
                ->exists();
        });

    }

    /**
     * Get sidebar permissions for a user based on specific permission requirements
     */
    private function getSidebarPermissions($user): array
    {
        // Initialize all permission keys to false to prevent undefined array key errors
        $permissions = [
            'dashboard' => true, // Dashboard is always visible
            'customers' => false,
            'commodities' => false,
            'quotations' => false,
            'invoices' => false,
            'expenses' => false,
            'record_payment' => false,
            'payment_receipts' => false,
            'reports' => false,
            'team' => false,
        ];

        // Admin bypasses all permission checks
        if ($user->isAdmin()) {
            $permissions = [
                'dashboard' => true,
                'customers' => true,
                'commodities' => true,
                'quotations' => true,
                'invoices' => true,
                'expenses' => true,
                'record_payment' => true,
                'payment_receipts' => true,
                'reports' => true,
                'team' => true,
            ];
        } else {
            // Map specific permissions to sidebar options
            $permissionMap = [
                'customers' => 'add_customer',
                'commodities' => 'manage_commodity',
                'quotations' => ['create_quote', 'edit_quote', 'convert_quote_to_invoice'],
                'invoices' => 'manage_invoices',
                'expenses' => ['add_expense', 'view_expenses'],
                'record_payment' => 'manage_invoices', // Payment recording requires invoice management
                'payment_receipts' => 'view_payment_receipts',
                'reports' => 'view_reports',
                'team' => 'manage_team',
            ];

            foreach ($permissionMap as $sidebarOption => $requiredPermissions) {
                $requiredPermissions = (array) $requiredPermissions;
                
                // Check if user has ANY of the required permissions
                foreach ($requiredPermissions as $permission) {
                    if ($user->hasPermission($permission)) {
                        $permissions[$sidebarOption] = true;
                        break;
                    }
                }
            }
        }

        // Compute dropdown visibility using OR logic across ALL child permissions
        $permissions['sales_billing_dropdown'] = 
            $permissions['customers'] || 
            $permissions['commodities'] || 
            $permissions['quotations'] || 
            $permissions['invoices'];
            
        $permissions['accounts_dropdown'] = 
            $permissions['expenses'] || 
            $permissions['record_payment'] || 
            $permissions['payment_receipts'];
            
        $permissions['management_dropdown'] = 
            $permissions['team'] || 
            $permissions['reports'];

        return $permissions;
    }

    private function getSectionName(?string $route): string
    {
        if ($route === null) {
            return 'General';
        }
        
        if (str_contains($route, 'users') || str_contains($route, 'vendors')) {
            return 'User & Vendor Management';
        }
        if (str_contains($route, 'warehouses') || str_contains($route, 'blocks')) {
            return 'Warehouse Management';
        }
        if (str_contains($route, 'materials') || str_contains($route, 'inventory') || str_contains($route, 'barcode')) {
            return 'Inventory & Materials';
        }
        if (str_contains($route, 'purchase-orders')) {
            return 'Procurement';
        }
        if (str_contains($route, 'quality-analysis')) {
            return 'Quality Control';
        }
        if (str_contains($route, 'reports')) {
            return 'Reports';
        }

        return 'General';
    }
}   