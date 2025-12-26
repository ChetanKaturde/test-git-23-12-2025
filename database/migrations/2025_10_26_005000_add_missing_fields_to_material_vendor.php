<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if business_id column exists
        if (!Schema::hasColumn('material_vendor', 'business_id')) {
            Schema::table('material_vendor', function (Blueprint $table) {
                $table->foreignId('business_id')->after('id')->constrained()->onDelete('cascade');
            });
            
            // Update existing records to have business_id from their vendor
            DB::statement('
                UPDATE material_vendor mv 
                JOIN vendors v ON mv.vendor_id = v.id 
                SET mv.business_id = v.business_id
            ');
        }
        
        // Check if notes column exists
        if (!Schema::hasColumn('material_vendor', 'notes')) {
            Schema::table('material_vendor', function (Blueprint $table) {
                $table->text('notes')->nullable();
            });
        }
        
        // Rename columns if they exist with old names
        if (Schema::hasColumn('material_vendor', 'unit_price') && !Schema::hasColumn('material_vendor', 'price_per_unit')) {
            Schema::table('material_vendor', function (Blueprint $table) {
                $table->renameColumn('unit_price', 'price_per_unit');
            });
        }
        
        if (Schema::hasColumn('material_vendor', 'quantity') && !Schema::hasColumn('material_vendor', 'min_order_qty')) {
            Schema::table('material_vendor', function (Blueprint $table) {
                $table->renameColumn('quantity', 'min_order_qty');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('material_vendor', 'price_per_unit')) {
            Schema::table('material_vendor', function (Blueprint $table) {
                $table->renameColumn('price_per_unit', 'unit_price');
            });
        }
        
        if (Schema::hasColumn('material_vendor', 'min_order_qty')) {
            Schema::table('material_vendor', function (Blueprint $table) {
                $table->renameColumn('min_order_qty', 'quantity');
            });
        }
        
        Schema::table('material_vendor', function (Blueprint $table) {
            if (Schema::hasColumn('material_vendor', 'business_id')) {
                $table->dropForeign(['business_id']);
                $table->dropColumn('business_id');
            }
            if (Schema::hasColumn('material_vendor', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};