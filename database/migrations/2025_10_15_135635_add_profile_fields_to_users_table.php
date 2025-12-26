<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_pincode')->nullable();
            $table->string('company_country')->default('India');
            $table->text('warehouse_address')->nullable();
            $table->string('warehouse_city')->nullable();
            $table->string('warehouse_state')->nullable();
            $table->string('warehouse_pincode')->nullable();
            $table->string('warehouse_country')->nullable();
            $table->boolean('warehouse_same_as_company')->default(false);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'company_address', 'company_city', 'company_state', 
                'company_pincode', 'company_country', 'warehouse_address', 
                'warehouse_city', 'warehouse_state', 'warehouse_pincode', 
                'warehouse_country', 'warehouse_same_as_company'
            ]);
        });
    }
};