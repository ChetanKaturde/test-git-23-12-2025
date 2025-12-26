<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add permission columns
            $table->boolean('can_manage_materials')->default(false)->after('role');
            $table->boolean('can_create_purchase_orders')->default(false)->after('can_manage_materials');
            $table->boolean('can_manage_machines')->default(false)->after('can_create_purchase_orders');
            $table->boolean('can_create_work_orders')->default(false)->after('can_manage_machines');
            $table->boolean('can_manage_invoices')->default(false)->after('can_create_work_orders');
            $table->boolean('can_manage_vendors')->default(false)->after('can_manage_invoices');
            $table->boolean('can_manage_team')->default(false)->after('can_manage_vendors');
        });

        // Set default permissions based on existing roles
        $this->setDefaultPermissions();
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'can_manage_materials',
                'can_create_purchase_orders', 
                'can_manage_machines',
                'can_create_work_orders',
                'can_manage_invoices',
                'can_manage_vendors',
                'can_manage_team'
            ]);
        });
    }

    private function setDefaultPermissions()
    {
        // Admin - full permissions
        DB::table('users')->where('role', 'admin')->update([
            'can_manage_materials' => true,
            'can_create_purchase_orders' => true,
            'can_manage_machines' => true,
            'can_create_work_orders' => true,
            'can_manage_invoices' => true,
            'can_manage_vendors' => true,
            'can_manage_team' => true,
        ]);

        // Manager - most permissions except team management
        DB::table('users')->where('role', 'manager')->update([
            'can_manage_materials' => true,
            'can_create_purchase_orders' => true,
            'can_manage_machines' => true,
            'can_create_work_orders' => true,
            'can_manage_invoices' => true,
            'can_manage_vendors' => true,
            'can_manage_team' => false,
        ]);

        // Inventory Manager - materials and purchase orders
        DB::table('users')->where('role', 'inventory_manager')->update([
            'can_manage_materials' => true,
            'can_create_purchase_orders' => true,
            'can_manage_machines' => false,
            'can_create_work_orders' => false,
            'can_manage_invoices' => false,
            'can_manage_vendors' => true,
            'can_manage_team' => false,
        ]);

        // Purchase Team - purchase orders and vendors
        DB::table('users')->where('role', 'purchase_team')->update([
            'can_manage_materials' => false,
            'can_create_purchase_orders' => true,
            'can_manage_machines' => false,
            'can_create_work_orders' => false,
            'can_manage_invoices' => false,
            'can_manage_vendors' => true,
            'can_manage_team' => false,
        ]);

        // Operator - work orders and machines
        DB::table('users')->where('role', 'operator')->update([
            'can_manage_materials' => false,
            'can_create_purchase_orders' => false,
            'can_manage_machines' => true,
            'can_create_work_orders' => true,
            'can_manage_invoices' => false,
            'can_manage_vendors' => false,
            'can_manage_team' => false,
        ]);

        // Viewer - no permissions
        DB::table('users')->where('role', 'viewer')->update([
            'can_manage_materials' => false,
            'can_create_purchase_orders' => false,
            'can_manage_machines' => false,
            'can_create_work_orders' => false,
            'can_manage_invoices' => false,
            'can_manage_vendors' => false,
            'can_manage_team' => false,
        ]);
    }
};