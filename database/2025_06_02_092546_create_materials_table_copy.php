<?php

// 2024_01_01_000004_create_materials_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('unit'); // kg, pieces, meters, etc.
            $table->decimal('standard_rate', 10, 2)->nullable();
            $table->integer('minimum_stock_level')->default(0);
            $table->integer('maximum_stock_level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('hsn_code')->nullable();
            $table->decimal('gst_rate', 5, 2)->default(0);
            $table->json('specifications')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
