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

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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
            try {
                $view->with('allWarehouses', Warehouse::all());
            } catch (\Exception $e) {
                // Database not ready or table doesn't exist, provide empty collection
                $view->with('allWarehouses', collect());
            }
        });

        // 3. Share navigation items dynamically based on permissions
        View::composer('*', function ($view) {
            $user = Auth::user();
            $navigationItems = [];

            if ($user) {
                try {
                    $modules = Module::where('is_active', 1)->get();
                    foreach ($modules as $module) {
                        $permission = $this->getPermissionFromRoute($module->route);
                        if ($this->userHasPermission($user, $permission)) {
                            $navigationItems[$permission] = [
                                'title' => $module->name,
                                'route' => $module->route,
                                'icon' => $module->icon,
                                'section' => $this->getSectionName($module->route),
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    // Modules table doesn't exist, use default navigation
                }

                // Always show dashboard
                $navigationItems['dashboard'] = [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'section' => 'Home',
                ];
            }

            $view->with('navigationItems', $navigationItems);
        });

        // 4. Share allowed modules based on subscription tier
        View::composer('*', function ($view) {
            $user = auth()->user();
            $allowedModules = [];
            
            if ($user && $user->business) {
                $allowedModules = $user->business->getAllowedModules();
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
    
            // 6. Add standardized permission directives using User model methods
            \Illuminate\Support\Facades\Blade::if('canViewModule', function ($module) {
                return auth()->user() && auth()->user()->canViewModule($module);
            });
    
            \Illuminate\Support\Facades\Blade::if('canCreateInModule', function ($module) {
                return auth()->user() && auth()->user()->canCreateInModule($module);
            });
    
            \Illuminate\Support\Facades\Blade::if('canEditInModule', function ($module) {
                return auth()->user() && auth()->user()->canEditInModule($module);
            });
    
            \Illuminate\Support\Facades\Blade::if('canDeleteInModule', function ($module) {
                return auth()->user() && auth()->user()->canDeleteInModule($module);
            });

    }

    private function userHasPermission($user, $permission): bool
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        $hasAnyPermissions = DB::table('permissions')
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAnyPermissions) {
            return false;
        }

        $module = Module::where('route', 'dashboard.' . $permission)
            ->orWhere('route', $permission)
            ->first();

        if (!$module) {
            return false;
        }

        return $user->hasModulePermission($module->id, 'view');
    }

    private function getPermissionFromRoute(?string $route): string
    {
        if ($route === null) {
            return '';
        }
        return str_replace(['dashboard.', '.index'], '', $route);
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