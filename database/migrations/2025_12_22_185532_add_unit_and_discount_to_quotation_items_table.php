<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('unit')->default('piece')->after('quantity');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('unit_price');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn(['unit', 'discount_percentage', 'discount_amount']);
        });
    }
};
