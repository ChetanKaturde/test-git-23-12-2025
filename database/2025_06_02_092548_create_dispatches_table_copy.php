<?php

// 2024_01_01_000010_create_dispatches_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('dispatch_number')->unique();
            $table->foreignId('batch_id')->constrained('batches');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('quantity', 10, 3);
            $table->string('unit');
            $table->string('dispatch_to'); // Department, Customer, etc.
            $table->text('purpose')->nullable();
            $table->date('dispatch_date');
            $table->time('dispatch_time')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('dispatched_by')->constrained('users');
            $table->enum('status', ['pending', 'dispatched', 'delivered', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dispatches');
    }
};
