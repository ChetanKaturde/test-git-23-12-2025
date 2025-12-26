<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('purchase_orders', 'supplier_contact')) {
                $table->string('supplier_contact')->nullable();
            }
            if (!Schema::hasColumn('purchase_orders', 'gst_amount')) {
                $table->decimal('gst_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('purchase_orders', 'final_amount')) {
                $table->decimal('final_amount', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('purchase_orders', 'payment_mode')) {
                $table->string('payment_mode')->nullable(); // Use string instead of enum
            }
            if (!Schema::hasColumn('purchase_orders', 'credit_days')) {
                $table->integer('credit_days')->nullable();
            }
            if (!Schema::hasColumn('purchase_orders', 'order_date')) {
                $table->date('order_date')->nullable();
            }
            if (!Schema::hasColumn('purchase_orders', 'expected_delivery')) {
                $table->date('expected_delivery')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $columnsToCheck = [
                'supplier_contact',
                'gst_amount', 
                'final_amount',
                'payment_mode',
                'credit_days',
                'order_date',
                'expected_delivery',
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('purchase_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
