<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['key' => 'quotation_management', 'name' => 'Quotation Management', 'is_quantity_based' => true, 'description' => 'Manage quotations'],
            ['key' => 'invoice_management', 'name' => 'Invoice Management', 'is_quantity_based' => true, 'description' => 'Manage invoices'],
            ['key' => 'expense_management', 'name' => 'Expense Management', 'is_quantity_based' => false, 'description' => 'Manage expenses'],
            ['key' => 'customer_management', 'name' => 'Customer Management', 'is_quantity_based' => true, 'description' => 'Manage customers'],
            ['key' => 'commodity_management', 'name' => 'Commodity Management', 'is_quantity_based' => false, 'description' => 'Manage commodities/materials'],
            ['key' => 'reports_analytics', 'name' => 'Reports & Analytics', 'is_quantity_based' => false, 'description' => 'Access reports and analytics'],
            ['key' => 'purchase_order_management', 'name' => 'Purchase Order Management', 'is_quantity_based' => false, 'description' => 'Manage purchase orders'],
            ['key' => 'inventory_management', 'name' => 'Inventory Management', 'is_quantity_based' => false, 'description' => 'Manage inventory'],
            ['key' => 'work_order_management', 'name' => 'Work Order Management', 'is_quantity_based' => false, 'description' => 'Manage work orders'],
            ['key' => 'quality_analysis', 'name' => 'Quality Analysis', 'is_quantity_based' => false, 'description' => 'Quality analysis tools'],
            ['key' => 'barcode_management', 'name' => 'Barcode Management', 'is_quantity_based' => false, 'description' => 'Barcode scanning and management'],
            ['key' => 'warehouse_management', 'name' => 'Warehouse Management', 'is_quantity_based' => false, 'description' => 'Warehouse and location management'],
            ['key' => 'team_management', 'name' => 'Team Management', 'is_quantity_based' => false, 'description' => 'Team and user management'],
            ['key' => 'notification_system', 'name' => 'Notification System', 'is_quantity_based' => false, 'description' => 'Notification system'],
        ];

        foreach ($features as $feature) {
            \App\Models\Feature::firstOrCreate($feature);
        }
    }
}
