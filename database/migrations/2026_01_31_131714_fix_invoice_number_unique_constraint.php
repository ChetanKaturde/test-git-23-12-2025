<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the global unique constraint
            $table->dropUnique(['invoice_number']);
            
            // Add business-scoped unique constraint
            $table->unique(['business_id', 'invoice_number']);
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop business-scoped unique constraint
            $table->dropUnique(['business_id', 'invoice_number']);
            
            // Restore global unique constraint
            $table->unique(['invoice_number']);
        });
    }
};