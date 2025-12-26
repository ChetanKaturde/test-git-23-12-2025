<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('name');
            $table->string('gstin')->nullable()->after('phone');
            
            // Company Address
            $table->text('company_address')->nullable()->after('address');
            $table->string('company_state')->nullable()->after('company_address');
            $table->string('company_city')->nullable()->after('company_state');
            $table->string('company_pincode', 6)->nullable()->after('company_city');
            $table->string('company_country')->nullable()->after('company_pincode');
            
            // Warehouse Address
            $table->text('warehouse_address')->nullable()->after('company_country');
            $table->string('warehouse_state')->nullable()->after('warehouse_address');
            $table->string('warehouse_city')->nullable()->after('warehouse_state');
            $table->string('warehouse_pincode', 6)->nullable()->after('warehouse_city');
            $table->string('warehouse_country')->nullable()->after('warehouse_pincode');
            
            // Bank Details
            $table->string('bank_holder_name')->nullable()->after('warehouse_country');
            $table->string('branch_name')->nullable()->after('bank_holder_name');
            $table->string('bank_name')->nullable()->after('branch_name');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
        });
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'business_name', 'gstin', 'company_address', 'company_state', 'company_city', 
                'company_pincode', 'company_country', 'warehouse_address', 'warehouse_state', 
                'warehouse_city', 'warehouse_pincode', 'warehouse_country', 'bank_holder_name', 
                'branch_name', 'bank_name', 'account_number', 'ifsc_code'
            ]);
        });
    }
};