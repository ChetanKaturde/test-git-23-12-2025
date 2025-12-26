<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_batches', function (Blueprint $table) {
            $table->foreignId('business_id')->after('id')->constrained();
        });
        
        // Update existing records to assign business_id based on material relationship
        DB::statement("
            UPDATE inventory_batches ib 
            JOIN materials m ON ib.material_id = m.id 
            SET ib.business_id = m.business_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_batches', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropColumn('business_id');
        });
    }
};