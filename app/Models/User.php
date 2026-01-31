<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'plain_password',
        'role',
        'is_active',
        'email_verified_at',
        'notification_preferences',
        'business_id',
        'team_id',
        'permissions',
        'phone',
        'company_address',
        'company_city',
        'company_state',
        'company_pincode',
        'company_country',
        'warehouse_address',
        'warehouse_city',
        'warehouse_state',
        'warehouse_pincode',
        'warehouse_country',
        'warehouse_same_as_company',
        'can_manage_materials',
        'can_create_purchase_orders',
        'can_manage_machines',
        'can_create_work_orders',
        'can_manage_invoices',
        'can_manage_vendors',
        'can_manage_team',
        'can_manage_quotations',
    ];

    protected $hidden = [
        'password',
        'plain_password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'notification_preferences' => 'array',
        'permissions' => 'array',
        'can_manage_materials' => 'boolean',
        'can_create_purchase_orders' => 'boolean',
        'can_manage_machines' => 'boolean',
        'can_create_work_orders' => 'boolean',
        'can_manage_invoices' => 'boolean',
        'can_manage_vendors' => 'boolean',
        'can_manage_team' => 'boolean',
        'can_manage_quotations' => 'boolean',
    ];

    /**
     * Team display name (for UI)
      */
     public function getTeamDisplayName()
     {
         return $this->team ? 'Team: ' . $this->team->team_name : 'Team: Not Assigned';
     }

    /**
     * Active status display
     */
    public function getStatusDisplayAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Main role checker. Supports string or array input.
     */
    public function hasRole($roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    /**
     * Individual role shortcut helpers
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isPurchaseTeam()
    {
        return $this->role === 'purchase_team';
    }

    public function isInventoryManager()
    {
        return $this->role === 'inventory_manager';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isViewer()
    {
        return $this->role === 'viewer';
    }

  
    /**
     * Is active checker
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for role filtering
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', '=', $role);
    }

    /**
     * Notification preferences helper
     */
    public function getNotificationPreferences()
    {
        return [
            'email' => $this->notification_preferences['email'] ?? true,
            'sms' => $this->notification_preferences['sms'] ?? false,
            'dashboard' => $this->notification_preferences['dashboard'] ?? true,
        ];
    }

    public function updateNotificationPreferences(array $preferences)
    {
        $this->update([
            'notification_preferences' => array_merge(
                $this->notification_preferences ?? [],
                $preferences
            )
        ]);
    }

    /**
     * Dashboard notifications shortcut
     */
    public function dashboardNotifications()
    {
        return $this->notifications()->where('type', '=', 'dashboard');
    }

    /**
     * Relationship: Purchase Orders created by this user
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by');
    }

    /**
     * Relationship: Business this user belongs to
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get current active subscription
     */
    // public function currentSubscription()
    // {
    //     if (!$this->business_id) return null;
    //     return $this->business->subscriptions()->active()->first();
    // }


    public function currentSubscription()
    {
        // Superadmin doesn't have subscriptions
        if ($this->role === 'superadmin') {
            return null;
        }
        
        if (!$this->business_id) return null;
        return $this->business->subscriptions()->active()->first();
    }


    /**
     * Check if user can use a feature
     */
    public function canUseFeature($featureName, $increment = 0)
    {
        $subscription = $this->currentSubscription();
        return $subscription ? $subscription->canUseFeature($featureName, $increment) : false;
    }

    /**
     * Check if feature is enabled for user's plan
     */
    public function isFeatureEnabled($featureName)
    {
        $subscription = $this->currentSubscription();
        return $subscription ? $subscription->isFeatureEnabled($featureName) : false;
    }

    /**
     * Check if business has a feature enabled
     */
    // public function businessHasFeature($feature)
    // {
    //     return $this->business && $this->business->hasFeature($feature);
    // }

    public function businessHasFeature($feature)
    {
        // Superadmin doesn't have business - return true to bypass checks
        if ($this->role === 'superadmin') {
            return true;
        }
        
        if (!$this->business) {
            return false;
        }
        
        return $this->business->hasFeature($feature);
    }

    /**
     * Check if user can access a feature
     * Admin: only checks if feature is in plan
     * Team member: checks if feature is in plan AND user has permission
     */
    public function canAccessFeature($feature, $permission = null)
    {
        // Superadmin bypasses all feature checks
        if ($this->role === 'superadmin') {
            return true;
        }

        // Check if feature is enabled in business plan
        if (!$this->businessHasFeature($feature)) {
            return false;
        }

        // Admin bypasses permission checks
        if ($this->isAdmin()) {
            return true;
        }

        // For team members, check permission if provided
        if ($permission && !$this->hasPermission($permission)) {
            return false;
        }

        return true;
    }

    /**
     * Relationship: Team this user belongs to
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
  
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function assignedWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }
     public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    // Alternative method to get permissions for a specific module
    public function hasPermissionFor($moduleName, $permissionType)
    {
        // Validate permission type
        $validPermissions = ['can_view', 'can_create', 'can_edit', 'can_delete'];
        if (!in_array($permissionType, $validPermissions)) {
            return false;
        }
        
        return $this->permissions()
            ->whereHas('module', function ($query) use ($moduleName) {
                $query->where('name', '=', $moduleName)
                      ->where('is_active', '=', 1);
            })
            ->where($permissionType, '=', true)
            ->exists();
    }



    public function getAccessibleModules()
    {
        return $this->modules()->wherePivot('can_view', true)->get();
    }
  
    /**
     * ✅ FIXED: Get modules for sidebar navigation
     */

/**
 * Get sidebar modules for the user based on their permissions
 * This method provides the modules that should be displayed in the sidebar
 */
public function getSidebarModules()
{
    return collect();
}

/**
 * Check if user has permission to view a specific module
 */
public function canViewModule($moduleName)
{
    if ($this->isAdmin()) {
        return true;
    }

    // Check subscription tier restrictions
    $tier = $this->business->subscription_tier ?? 'full_erp';
    $allowedModules = match ($tier) {
        'billing_sales' => ['customers', 'quotations', 'invoices', 'team', 'reports'],
        default => ['materials', 'machines', 'work_orders', 'inventory', 'vendors', 'purchase_orders', 'customers', 'quotations', 'invoices', 'team', 'reports'],
    };
    
    if (!in_array($moduleName, $allowedModules)) {
        return false;
    }

    return $this->permissions()
        ->whereHas('module', function ($query) use ($moduleName) {
            $query->where('name', $moduleName);
        })
        ->where('can_view', true)
        ->exists();
}

public function canCreateInModule($moduleName)
{
    if ($this->isAdmin()) return true;
    return $this->permissions()->whereHas('module', fn($q) => $q->where('name', $moduleName))->where('can_create', true)->exists();
}

public function canEditInModule($moduleName)
{
    if ($this->isAdmin()) return true;
    return $this->permissions()->whereHas('module', fn($q) => $q->where('name', $moduleName))->where('can_edit', true)->exists();
}

public function canDeleteInModule($moduleName)
{
    if ($this->isAdmin()) return true;
    return $this->permissions()->whereHas('module', fn($q) => $q->where('name', $moduleName))->where('can_delete', true)->exists();
}

public function getDefaultPermissions()
{
    $defaults = [
        'admin' => ['materials' => 'full', 'machines' => 'full', 'work_orders' => 'full', 'inventory' => 'full', 'purchase_orders' => 'full', 'vendors' => 'full', 'invoices' => 'full', 'quotations' => 'full', 'team' => 'full', 'reports' => 'full'],
        'manager' => ['materials' => 'edit', 'machines' => 'edit', 'work_orders' => 'full', 'inventory' => 'edit', 'purchase_orders' => 'edit', 'vendors' => 'view', 'invoices' => 'edit', 'quotations' => 'full', 'reports' => 'view'],
        'inventory_manager' => ['materials' => 'edit', 'inventory' => 'full', 'purchase_orders' => 'view', 'vendors' => 'view', 'reports' => 'view'],
        'purchase_team' => ['materials' => 'view', 'purchase_orders' => 'full', 'vendors' => 'full', 'inventory' => 'view', 'reports' => 'view'],
        'operator' => ['materials' => 'view', 'machines' => 'edit', 'work_orders' => 'edit', 'inventory' => 'view'],
        'viewer' => ['materials' => 'view', 'machines' => 'view', 'work_orders' => 'view', 'inventory' => 'view', 'purchase_orders' => 'view', 'vendors' => 'view', 'invoices' => 'view', 'quotations' => 'view', 'reports' => 'view']
    ];
    return $defaults[$this->role] ?? [];
}

/**
 * Check if user has permission to edit a specific module
 */
public function canEditModule($moduleName)
{
    if ($this->isAdmin()) {
        return true;
    }

    return $this->permissions()
        ->whereHas('module', function ($query) use ($moduleName) {
            $query->where('name', $moduleName);
        })
        ->where('can_edit', true)
        ->exists();
}

    /**
     * ✅ ADDITIONAL: Helper method to check if user has any permission for a module
     */
 public function hasModulePermission($moduleId, $action = 'view'): bool
{
    $column = 'can_' . $action;

    return $this->permissions()
        ->where('module_id', $moduleId)
        ->where($column, true)
        ->exists();
}

    /**
     * ✅ ADDITIONAL: Get user's specific permissions for a module
     */
    public function getModulePermissions($moduleId)
    {
        if ($this->role === 'admin') {
            return [
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => true,
            ];
        }

        $permission = $this->permissions()->where('module_id', $moduleId)->first();

        if (!$permission) {
            return [
                'can_view' => false,
                'can_create' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        return [
            'can_view' => (bool) $permission->can_view,
            'can_create' => (bool) $permission->can_create,
            'can_edit' => (bool) $permission->can_edit,
            'can_delete' => (bool) $permission->can_delete,
        ];
    }

    /**
     * Check if user has a specific flexible permission (for team members only)
     */
    public function hasPermission($permission)
    {
        // Note: Admin does NOT use this method - they bypass permission checks
        $permissions = $this->permissions ?? [];

        // Handle permission key variations
        $permissionMappings = [
            'create_quotation' => ['create_quotation', 'create_quote'],
            'edit_quotation' => ['edit_quotation', 'edit_quote'],
            'view_quotation' => ['view_quotation', 'view_quote'],
            'delete_quotation' => ['delete_quotation', 'delete_quote'],
            'convert_quotation_to_invoice' => ['convert_quotation_to_invoice', 'convert_quote_to_invoice'],
        ];

        if (isset($permissionMappings[$permission])) {
            return !empty(array_intersect($permissionMappings[$permission], $permissions));
        }

        return in_array($permission, $permissions);
    }

    /**
     * Check if user has any quotation permission
     * Admin: only checks if feature is enabled in plan
     * Team member: checks if feature is enabled AND has permissions
     */
    public function hasAnyQuotationPermission()
    {
        if (!$this->businessHasFeature('quotation_management')) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        }

        $quotationPermissions = ['view_quotation', 'create_quotation', 'edit_quotation', 'delete_quotation', 'create_quote', 'edit_quote'];
        $permissions = $this->permissions ?? [];
        return !empty(array_intersect($quotationPermissions, $permissions));
    }

    /**
     * ✅ FIXED: Check if user can access a feature action
     * Plan must include feature (applies to everyone)
     * Admin: can use features in their plan
     * Team member: needs permission for features in their plan
     */
    public function canAccessFeatureAction($feature, $action = null)
    {
        // Superadmin bypasses all checks
        if ($this->role === 'superadmin') {
            return true;
        }

        // Plan must include feature (applies to everyone)
        if (!$this->business || !$this->business->hasFeature($feature)) {
            return false;
        }

        // Admin can use features in their plan
        if ($this->isAdmin()) {
            return true;
        }

        // Team members need permission if action is specified
        if ($action) {
            return $this->hasPermission($action);
        }

        return false;
    }

    /**
     * Get all flexible permissions for the user
     */
    public function getPermissions()
    {
        return $this->permissions ?? [];
    }

    /**
     * Set flexible permissions for the user
     */
    public function setPermissions(array $permissions)
    {
        $this->update(['permissions' => array_unique($permissions)]);
    }
}