<?php

// 2024_01_01_000003_create_vendors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code')->unique();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('India');
            $table->string('pincode');
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->enum('payment_terms', ['COD', '15_days', '30_days', '45_days', '60_days', '90_days']);
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->json('bank_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
};