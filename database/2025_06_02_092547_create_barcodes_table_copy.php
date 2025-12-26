<?php

// 2024_01_01_000009_create_barcodes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_number')->unique();
            $table->string('barcode_type')->default('CODE128'); // CODE128, EAN13, etc.
            $table->morphs('barcodeable'); // Can be attached to batch, inventory_item, etc.
            $table->string('file_path')->nullable();
            $table->boolean('is_printed')->default(false);
            $table->timestamp('printed_at')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barcodes');
    }
};