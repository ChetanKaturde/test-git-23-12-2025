<?php

// 2024_01_01_000015_create_report_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->string('report_type'); // purchase, inventory, dispatch, etc.
            $table->json('filters_applied')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['generating', 'completed', 'failed'])->default('generating');
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_logs');
    }
};

