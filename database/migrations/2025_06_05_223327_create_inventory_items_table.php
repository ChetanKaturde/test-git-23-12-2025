<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->nullable()->constrained('inventory_batches')->onDelete('set null');
            
            // Item details
            $table->decimal('quantity', 10, 3)->default(0);
            $table->decimal('weight', 10, 3)->nullable();
            $table->string('status')->default('available'); // e.g. available, reserved, used, etc.
            $table->string('location')->nullable(); // Storage location
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
