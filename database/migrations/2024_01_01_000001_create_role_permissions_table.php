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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('permission');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Ensure unique role-permission combinations
            $table->unique(['role', 'permission']);
            
            // Add indexes for better performance
            $table->index('role');
            $table->index('permission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};