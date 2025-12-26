<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add business_id to key tables
        $tables = [
            'users',
            'materials', 
            'vendors',
            'purchase_orders',
            'warehouses',
            'inventory_batches',
            'modules',
            'permissions'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade');
                    $table->index('business_id');
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'users',
            'materials', 
            'vendors',
            'purchase_orders',
            'warehouses',
            'inventory_batches',
            'modules',
            'permissions'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['business_id']);
                    $table->dropColumn('business_id');
                });
            }
        }
    }
};