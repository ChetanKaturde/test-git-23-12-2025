<?php

// 2024_01_01_000007_create_batches_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->date('received_date');
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('received_quantity', 10, 3);
            $table->decimal('current_quantity', 10, 3);
            $table->string('unit');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 15, 2);
            $table->enum('status', ['received', 'quality_check', 'approved', 'rejected', 'hold'])->default('received');
            $table->string('location')->nullable();
            $table->text('quality_notes')->nullable();
            $table->foreignId('received_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('batches');
    }
};