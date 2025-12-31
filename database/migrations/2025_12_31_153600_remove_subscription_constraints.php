<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, we need to recreate the table to remove constraints
        if (DB::getDriverName() === 'sqlite') {
            // Create temporary table
            Schema::create('businesses_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('legal_name')->nullable();
                $table->string('slug')->unique();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('subscription_plan')->default('free'); // No constraints
                $table->string('subscription_tier')->default('full_erp');
                $table->string('template')->default('service');
                $table->datetime('subscription_expires_at')->nullable();
                $table->string('logo_path')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('pin_code')->nullable();
                $table->string('country')->nullable();
                $table->string('gstin')->nullable();
                $table->string('pan')->nullable();
                $table->string('hsn_prefix')->nullable();
                $table->string('currency')->default('INR');
                $table->date('financial_year_start')->nullable();
                $table->text('payment_terms')->nullable();
                $table->text('terms_and_conditions')->nullable();
                $table->string('timezone')->nullable();
                $table->timestamps();
            });
            
            // Copy data
            DB::statement('INSERT INTO businesses_temp SELECT * FROM businesses');
            
            // Drop old table and rename
            Schema::drop('businesses');
            Schema::rename('businesses_temp', 'businesses');
        }
    }

    public function down(): void
    {
        // Revert if needed
    }
};