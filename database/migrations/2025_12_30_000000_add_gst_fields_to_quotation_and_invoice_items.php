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
        // Add GST fields to quotation_items
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->decimal('list_price', 10, 2)->default(0)->after('unit_price');
            $table->decimal('net_price', 10, 2)->default(0)->after('list_price');
            $table->string('hsn_code', 20)->nullable()->after('net_price');
            $table->decimal('taxable_value', 10, 2)->default(0)->after('hsn_code');
            $table->decimal('cgst_amount', 10, 2)->default(0)->after('taxable_value');
            $table->decimal('sgst_amount', 10, 2)->default(0)->after('cgst_amount');
            $table->decimal('igst_amount', 10, 2)->default(0)->after('sgst_amount');
        });

        // Add GST fields to invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('list_price', 10, 2)->default(0)->after('unit_price');
            $table->decimal('net_price', 10, 2)->default(0)->after('list_price');
            $table->string('hsn_code', 20)->nullable()->after('net_price');
            $table->decimal('taxable_value', 10, 2)->default(0)->after('hsn_code');
            $table->decimal('cgst_amount', 10, 2)->default(0)->after('taxable_value');
            $table->decimal('sgst_amount', 10, 2)->default(0)->after('cgst_amount');
            $table->decimal('igst_amount', 10, 2)->default(0)->after('sgst_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn(['list_price', 'net_price', 'hsn_code', 'taxable_value', 'cgst_amount', 'sgst_amount', 'igst_amount']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['list_price', 'net_price', 'hsn_code', 'taxable_value', 'cgst_amount', 'sgst_amount', 'igst_amount']);
        });
    }
};