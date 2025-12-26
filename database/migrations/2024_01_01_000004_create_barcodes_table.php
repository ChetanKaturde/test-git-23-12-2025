<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodesTable extends Migration
{
    public function up(): void
    {
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();

            $table->string('barcode_number')->unique();

            // Foreign keys
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();

            // Material & supplier data
            $table->string('material_name');
            $table->string('material_code')->nullable();
            $table->string('supplier_name')->nullable();

            // Inventory data
            $table->integer('quantity')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();

            // Other barcode metadata
            $table->enum('barcode_type', ['standard', 'qr', 'both']);
            $table->enum('status', ['active', 'inactive', 'damaged', 'expired'])->default('active');
            $table->text('qr_code_data')->nullable();
            $table->string('storage_location')->nullable();
            $table->string('quality_grade')->nullable();

            $table->date('expiry_date')->nullable();
            $table->timestamp('last_scanned_at')->nullable();

            $table->integer('print_count')->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();
            // Foreign keys will be added later after all tables are created
            // $table->foreign('batch_id')->references('id')->on('inventory_batches')->onDelete('cascade');
            // $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('set null');
            // $table->foreign('material_id')->references('id')->on('materials')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barcodes');
    }
}
