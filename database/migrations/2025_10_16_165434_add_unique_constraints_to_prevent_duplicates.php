<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add unique constraints for vendors within business
        if (!$this->indexExists('vendors', 'vendors_phone_business_unique')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->unique(['phone', 'business_id'], 'vendors_phone_business_unique');
            });
        }
        
        if (!$this->indexExists('vendors', 'vendors_account_business_unique')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->unique(['account_number', 'business_id'], 'vendors_account_business_unique');
            });
        }

        // Add unique constraints for materials within business
        if (!$this->indexExists('materials', 'materials_name_business_unique')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->unique(['name', 'business_id'], 'materials_name_business_unique');
            });
        }
        
        if (!$this->indexExists('materials', 'materials_code_business_unique')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->unique(['code', 'business_id'], 'materials_code_business_unique');
            });
        }

        // Add unique constraint for warehouses
        if (Schema::hasColumn('warehouses', 'business_id') && !$this->indexExists('warehouses', 'warehouses_name_business_unique')) {
            Schema::table('warehouses', function (Blueprint $table) {
                $table->unique(['name', 'business_id'], 'warehouses_name_business_unique');
            });
        }
    }
    
    private function indexExists($table, $indexName)
    {
        $indexes = collect(DB::select("SHOW INDEX FROM {$table}"))->pluck('Key_name');
        return $indexes->contains($indexName);
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            try {
                $table->dropUnique('vendors_phone_business_unique');
            } catch (\Exception $e) {}
            try {
                $table->dropUnique('vendors_account_business_unique');
            } catch (\Exception $e) {}
        });

        Schema::table('materials', function (Blueprint $table) {
            try {
                $table->dropUnique('materials_name_business_unique');
            } catch (\Exception $e) {}
            try {
                $table->dropUnique('materials_code_business_unique');
            } catch (\Exception $e) {}
        });

        Schema::table('warehouses', function (Blueprint $table) {
            if (Schema::hasColumn('warehouses', 'business_id')) {
                try {
                    $table->dropUnique('warehouses_name_business_unique');
                } catch (\Exception $e) {}
            }
        });
    }
};