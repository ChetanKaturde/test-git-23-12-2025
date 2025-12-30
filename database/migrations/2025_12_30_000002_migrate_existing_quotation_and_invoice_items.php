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
        // Migrate existing quotation_items
        DB::table('quotation_items')->where('list_price', 0)->update([
            'list_price' => DB::raw('unit_price'),
            'net_price' => DB::raw('unit_price'),
            'discount_percentage' => 0,
            'taxable_value' => DB::raw('unit_price * quantity'),
        ]);

        // Migrate existing invoice_items
        DB::table('invoice_items')->where('list_price', 0)->update([
            'list_price' => DB::raw('unit_price'),
            'net_price' => DB::raw('unit_price'),
            'discount_percentage' => 0,
            'taxable_value' => DB::raw('unit_price * quantity'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's just setting defaults
    }
};