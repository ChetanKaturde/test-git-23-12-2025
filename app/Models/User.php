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
        'role',
        'is_active',
        'email_verified_at',
        'notification_preferences',
        'business_id',
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
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'notification_preferences' => 'array',
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
     * Role display name (for UI)
     */
    public function getRoleDisplayName()
    {
        $roleDisplayNames = [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'purchase_team' => 'Purchase Team',
            'inventory_manager' => 'Inventory Manager',
            'operator' => 'Machine Operator',
            'viewer' => 'Viewer',
            'user' => 'User',
        ];

        return $roleDisplayNames[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
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
}