<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default modules
        \DB::table('modules')->insert([
            ['name' => 'materials', 'display_name' => 'Materials Management', 'description' => 'Manage raw materials and inventory'],
            ['name' => 'machines', 'display_name' => 'Machine Operations', 'description' => 'Manage machines and equipment'],
            ['name' => 'work_orders', 'display_name' => 'Work Orders', 'description' => 'Create and manage production orders'],
            ['name' => 'inventory', 'display_name' => 'Inventory Control', 'description' => 'Track stock levels and batches'],
            ['name' => 'purchase_orders', 'display_name' => 'Purchase Orders', 'description' => 'Manage supplier orders'],
            ['name' => 'vendors', 'display_name' => 'Vendor Management', 'description' => 'Manage supplier relationships'],
            ['name' => 'invoices', 'display_name' => 'Invoice Management', 'description' => 'Generate and track invoices'],
            ['name' => 'team', 'display_name' => 'Team Management', 'description' => 'Manage team members and roles'],
            ['name' => 'reports', 'display_name' => 'Reports & Analytics', 'description' => 'View business reports'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
};