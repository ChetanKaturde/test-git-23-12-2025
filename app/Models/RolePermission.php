<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'permission',
        'description',
    ];

    /**
     * Define available permissions for each role
     */
    public static function getDefaultPermissions()
    {
        return [
            'admin' => [
                'manage_users',
                'manage_inventory',
                'manage_purchases',
                'view_reports',
                'system_settings',
            ],
            'purchase_team' => [
                'create_purchase_orders',
                'edit_purchase_orders',
                'view_purchase_orders',
                'manage_suppliers',
            ],
            'inventory_manager' => [
                'manage_inventory',
                'view_inventory_reports',
                'update_stock_levels',
                'manage_categories',
            ],
        ];
    }

    /**
     * Seed default permissions
     */
    public static function seedDefaultPermissions()
    {
        $permissions = self::getDefaultPermissions();
        
        foreach ($permissions as $role => $rolePermissions) {
            foreach ($rolePermissions as $permission) {
                self::firstOrCreate([
                    'role' => $role,
                    'permission' => $permission,
                ]);
            }
        }
    }

    /**
     * Get permissions for a specific role
     */
    public static function getPermissionsForRole($role)
    {
        return self::where('role', $role)->pluck('permission')->toArray();
    }
}