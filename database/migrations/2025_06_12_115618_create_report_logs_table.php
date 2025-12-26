<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->string('report_type');
            $table->json('filters_applied')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Optional: add foreign key to users table
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_logs');
    }
};
