<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
//use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if purchase_id column exists before modifying
        if (Schema::hasColumn('purchase_orders', 'purchase_id')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->string('purchase_id')->nullable()->change();
            });
        } else {
            // If purchase_id doesn't exist, add it
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->string('purchase_id')->nullable();
            });
        }
    }

    public function down()
    {
        try {
            if (Schema::hasColumn('purchase_orders', 'purchase_id')) {
                Schema::table('purchase_orders', function (Blueprint $table) {
                    $table->dropColumn('purchase_id');
                });
            }
        } catch (Exception $e) {
            // Log error but don't fail migration rollback
            Log::warning('Failed to drop purchase_id column: ' . $e->getMessage());
        }
    }
};
