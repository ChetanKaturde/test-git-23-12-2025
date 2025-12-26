// database/migrations/xxxx_xx_xx_create_purchase_orders_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->unsignedBigInteger('vendor_id');
            // Foreign key will be added later
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
    $table->timestamp('po_date')->default(DB::raw('CURRENT_TIMESTAMP')); // ✅ works
            $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};