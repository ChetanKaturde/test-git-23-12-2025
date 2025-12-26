<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('wo_number')->unique();
            $table->foreignId('machine_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['business_id', 'status']);
            $table->index(['machine_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
};