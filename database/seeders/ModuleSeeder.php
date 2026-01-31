<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            ['name' => 'materials', 'display_name' => 'Materials Management', 'description' => 'Manage raw materials and inventory', 'is_active' => true],
            ['name' => 'machines', 'display_name' => 'Machine Operations', 'description' => 'Manage machines and equipment', 'is_active' => true],
            ['name' => 'work_orders', 'display_name' => 'Work Orders', 'description' => 'Create and manage production orders', 'is_active' => true],
            ['name' => 'inventory', 'display_name' => 'Inventory Control', 'description' => 'Track stock levels and batches', 'is_active' => true],
            ['name' => 'purchase_orders', 'display_name' => 'Purchase Orders', 'description' => 'Manage supplier orders', 'is_active' => true],
            ['name' => 'vendors', 'display_name' => 'Vendor Management', 'description' => 'Manage supplier relationships', 'is_active' => true],
            ['name' => 'customers', 'display_name' => 'Customer Management', 'description' => 'Manage customer relationships', 'is_active' => true],
            ['name' => 'invoices', 'display_name' => 'Invoice Management', 'description' => 'Generate and track invoices', 'is_active' => true],
            ['name' => 'quotations', 'display_name' => 'Quotation Management', 'description' => 'Create and manage customer quotations', 'is_active' => true],
            ['name' => 'team', 'display_name' => 'Team Management', 'description' => 'Manage team members and roles', 'is_active' => true],
            ['name' => 'reports', 'display_name' => 'Reports & Analytics', 'description' => 'View business reports', 'is_active' => true],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(
                ['name' => $module['name']],
                $module
            );
        }
    }
}