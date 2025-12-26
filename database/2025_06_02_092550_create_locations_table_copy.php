<?php

// 2024_01_01_000018_create_locations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['warehouse', 'shop_floor', 'quality_lab', 'office', 'other']);
            $table->string('address')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};