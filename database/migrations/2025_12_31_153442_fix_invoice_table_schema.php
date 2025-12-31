<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Make customer_address nullable
            $table->text('customer_address')->nullable()->change();
            
            // Add missing date fields if they don't exist
            if (!Schema::hasColumn('invoices', 'issue_date')) {
                $table->date('issue_date')->default(now())->after('status');
            }
            if (!Schema::hasColumn('invoices', 'due_date')) {
                $table->date('due_date')->nullable()->after('issue_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->text('customer_address')->nullable(false)->change();
        });
    }
};