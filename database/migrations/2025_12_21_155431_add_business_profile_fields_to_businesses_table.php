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
        Schema::table('businesses', function (Blueprint $table) {
            // Columns already exist from 2025_12_21_165547_add_legal_name_and_hsn_prefix_to_businesses_table
            // This migration is kept for consistency but does nothing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['legal_name', 'hsn_prefix']);
        });
    }
};
