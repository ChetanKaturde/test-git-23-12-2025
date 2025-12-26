<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->enum('customer_type', ['individual', 'business'])->default('business');
            $table->string('payment_terms')->default('due_on_receipt');
            $table->string('default_currency')->default('INR');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['billing_address', 'shipping_address', 'customer_type', 'payment_terms', 'default_currency']);
        });
    }
};