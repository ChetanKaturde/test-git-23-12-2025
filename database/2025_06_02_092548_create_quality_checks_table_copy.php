<?php

// 2024_01_01_000012_create_quality_checks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->string('qc_number')->unique();
            $table->foreignId('batch_id')->constrained('batches');
            $table->foreignId('material_id')->constrained('materials');
            $table->date('check_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'hold'])->default('pending');
            $table->json('test_parameters')->nullable(); // Store test results as JSON
            $table->decimal('sample_quantity', 10, 3)->nullable();
            $table->text('observations')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('checked_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quality_checks');
    }
};