<?php

// 2024_01_01_000014_create_activity_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->morphs('subject');
            $table->morphs('causer');
            $table->json('properties')->nullable();
            $table->string('event')->nullable();
            $table->timestamp('created_at');
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};