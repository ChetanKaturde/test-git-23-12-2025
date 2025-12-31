<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // Drop existing constraint and recreate with all plan options
            $table->string('subscription_plan')->default('free')->change();
        });
    }

    public function down(): void
    {
        // Revert if needed
    }
};