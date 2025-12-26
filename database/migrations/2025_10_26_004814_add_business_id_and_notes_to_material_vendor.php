<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_vendor', function (Blueprint $table) {
            $table->foreignId('business_id')->after('id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable()->after('quantity');
            $table->renameColumn('unit_price', 'price_per_unit');
            $table->renameColumn('quantity', 'min_order_qty');
        });
        
        // Update existing records to have business_id from their vendor
        DB::statement('
            UPDATE material_vendor mv 
            JOIN vendors v ON mv.vendor_id = v.id 
            SET mv.business_id = v.business_id
        ');
    }

    public function down(): void
    {
        Schema::table('material_vendor', function (Blueprint $table) {
            $table->renameColumn('price_per_unit', 'unit_price');
            $table->renameColumn('min_order_qty', 'quantity');
            $table->dropForeign(['business_id']);
            $table->dropColumn(['business_id', 'notes']);
        });
    }
};