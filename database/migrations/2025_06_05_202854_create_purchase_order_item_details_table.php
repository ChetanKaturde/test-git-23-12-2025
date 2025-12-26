<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderItemDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_order_item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_item_id')->constrained()->cascadeOnDelete();
            $table->decimal('gst_rate', 5, 2)->default(18.00);
            $table->decimal('gst_amount', 10, 2)->default(0);
            $table->decimal('net_price', 10, 2)->default(0);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_order_item_details');
    }
}
