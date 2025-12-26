<?php

// 2024_01_01_000008_create_inventory_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('batch_id')->constrained('batches');
            $table->decimal('available_quantity', 10, 3)->default(0);
            $table->decimal('dispatched_quantity', 10, 3)->default(0);
            $table->decimal('returned_quantity', 10, 3)->default(0);
            $table->decimal('error_quantity', 10, 3)->default(0);
            $table->decimal('reserved_quantity', 10, 3)->default(0);
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'low_stock', 'out_of_stock', 'expired'])->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
};
