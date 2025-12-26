<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            if (!Schema::hasColumn('businesses', 'logo_path')) {
                $table->string('logo_path')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'pin_code')) {
                $table->string('pin_code')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'country')) {
                $table->string('country')->default('India');
            }
            if (!Schema::hasColumn('businesses', 'gstin')) {
                $table->string('gstin')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'pan')) {
                $table->string('pan')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'currency')) {
                $table->string('currency')->default('INR');
            }
            if (!Schema::hasColumn('businesses', 'financial_year_start')) {
                $table->date('financial_year_start')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'terms_and_conditions')) {
                $table->text('terms_and_conditions')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'timezone')) {
                $table->string('timezone')->default('Asia/Kolkata');
            }
        });
    }

    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path', 'address', 'city', 'state', 'pin_code', 'country',
                'phone', 'email', 'gstin', 'pan', 'currency', 'financial_year_start',
                'terms_and_conditions', 'timezone'
            ]);
        });
    }
};