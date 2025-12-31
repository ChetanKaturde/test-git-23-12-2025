<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Update role column to allow more roles
            $table->string('role')->default('admin')->change();
        });
    }

    public function down(): void
    {
        // Revert if needed
    }
};