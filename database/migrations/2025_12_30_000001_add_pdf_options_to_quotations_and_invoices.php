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
        Schema::table('quotations', function (Blueprint $table) {
            $table->json('pdf_options')->nullable()->after('notes');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->json('pdf_options')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('pdf_options');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('pdf_options');
        });
    }
};