<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('material_id')->constrained();
            $table->decimal('received_weight', 8, 3);
            $table->integer('received_quantity');
            $table->decimal('current_weight', 8, 3);
            $table->integer('current_quantity');
            $table->string('storage_location')->nullable();
            $table->date('received_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'damaged', 'exhausted'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_batches');
    }
};
