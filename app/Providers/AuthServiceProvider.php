<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Warehouse;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\WarehouseBlockPolicy;
use App\Models\WarehouseBlock;
use App\Models\Material;
use App\Policies\MaterialPolicy;
use App\Models\PurchaseOrder;
use App\Policies\PurchaseOrderPolicy;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
   protected $policies = [
    User::class => UserPolicy::class,
    Warehouse::class => WarehousePolicy::class,
    Material::class => MaterialPolicy::class,
    PurchaseOrder::class => PurchaseOrderPolicy::class, // ✅ Add this line
];


    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Optional: Define gates if you prefer gates over policies
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
    
      // Define the manage-warehouses gate that your routes are checking for
        Gate::define('manage-warehouses', function (User $user) {
            return in_array($user->role, ['admin', 'inventory_manager']);
        });

        // Optional: You can also define other gates if needed
        Gate::define('manage-inventory', function (User $user) {
            return in_array($user->role, ['admin', 'inventory_manager', 'warehouse_staff']);
        });

        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });
        
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        // Module-based permission gates
        Gate::define('view-users', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('user_management');
        });

        Gate::define('view-warehouses', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('warehouse_management');
        });

        Gate::define('view-warehouse-blocks', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('view_blocks');
        });

        Gate::define('view-materials', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('materials');
        });

        Gate::define('view-vendors', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('vendor_management');
        });

        Gate::define('view-purchase-orders', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('purchase_orders');
        });

        Gate::define('view-inventory', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('inventory_control');
        });

        Gate::define('view-barcodes', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('barcode_management');
        });

        Gate::define('view-quality', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('quality_analysis');
        });

        Gate::define('view-reports', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('report_analysis');
        });

        // Edit permissions
        Gate::define('edit-materials', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('materials');
        });

        Gate::define('edit-vendors', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('vendor_management');
        });

        Gate::define('edit-purchase-orders', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('purchase_orders');
        });

        Gate::define('edit-inventory', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('inventory_control');
        });

        Gate::define('edit-warehouses', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('warehouse_management');
        });

        Gate::define('edit-barcodes', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('barcode_management');
        });

        Gate::define('edit-quality', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('quality_analysis');
        });

        // Machine and Work Order permissions
        Gate::define('view-machines', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('machines');
        });

        Gate::define('view-work-orders', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('work_orders');
        });

        Gate::define('edit-machines', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('machines');
        });

        Gate::define('edit-work-orders', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('work_orders');
        });

        // Invoice permissions
        Gate::define('view-invoices', function (User $user) {
            return $user->isAdmin() || $user->canViewModule('invoices');
        });

        Gate::define('create-invoices', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('invoices');
        });

        Gate::define('edit-invoices', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('invoices');
        });

        Gate::define('delete-invoices', function (User $user) {
            return $user->isAdmin() || $user->canEditModule('invoices');
        });
      
  
}
}