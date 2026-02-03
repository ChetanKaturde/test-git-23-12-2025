<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop the global unique constraint on 'sku' if it exists
            try {
                $table->dropUnique(['sku']);
            } catch (\Exception $e) {
                // Constraint might not exist or already dropped
            }
        });

        // Add business-scoped unique constraint for SKU if it doesn't exist
        if (!$this->indexExists('materials', 'materials_sku_business_unique')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->unique(['sku', 'business_id'], 'materials_sku_business_unique');
            });
        }
    }

    public function down()
    {
        // Remove business-scoped constraint for SKU
        Schema::table('materials', function (Blueprint $table) {
            try {
                $table->dropUnique('materials_sku_business_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
        });

        // Re-add global unique constraint on sku (original state)
        Schema::table('materials', function (Blueprint $table) {
            $table->unique('sku');
        });
    }
    
    private function indexExists($table, $indexName)
    {
        try {
            $indexes = collect(DB::select("SHOW INDEX FROM {$table}"))->pluck('Key_name');
            return $indexes->contains($indexName);
        } catch (\Exception $e) {
            return false;
        }
    }
};