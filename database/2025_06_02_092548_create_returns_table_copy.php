<?php

// 2024_01_01_000011_create_returns_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('dispatch_id')->nullable()->constrained('dispatches');
            $table->foreignId('batch_id')->constrained('batches');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('quantity', 10, 3);
            $table->string('unit');
            $table->date('return_date');
            $table->time('return_time')->nullable();
            $table->enum('return_type', ['damage', 'defect', 'excess', 'expired', 'other']);
            $table->text('reason');
            $table->enum('action_taken', ['restock', 'repair', 'dispose', 'vendor_return'])->nullable();
            $table->foreignId('returned_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('returns');
    }
};
