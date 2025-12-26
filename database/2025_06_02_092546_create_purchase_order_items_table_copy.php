<?php

// 2024_01_01_000006_create_purchase_order_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('quantity', 10, 3);
            $table->string('unit');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('taxable_amount', 10, 2);
            $table->decimal('gst_rate', 5, 2);
            $table->decimal('gst_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('received_quantity', 10, 3)->default(0);
            $table->decimal('pending_quantity', 10, 3)->default(0);
            $table->text('specifications')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
