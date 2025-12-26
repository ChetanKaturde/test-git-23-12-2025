<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('planned_quantity', 10, 2);
            $table->decimal('actual_quantity', 10, 2)->default(0);
            $table->decimal('waste_quantity', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['work_order_id', 'material_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_consumptions');
    }
};