<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('purchase_order_items', function (Blueprint $table) {
        $table->decimal('gst_rate', 5, 2)->default(18.00);
        $table->decimal('gst_amount', 10, 2)->default(0);
        $table->decimal('net_price', 10, 2)->default(0);
        $table->string('batch_number')->nullable();
        $table->date('expiry_date')->nullable();
    });
}

public function down()
{
    Schema::table('purchase_order_items', function (Blueprint $table) {
        $table->dropColumn(['gst_rate', 'gst_amount', 'net_price', 'batch_number', 'expiry_date']);
    });
}

};
