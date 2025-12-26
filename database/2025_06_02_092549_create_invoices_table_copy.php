<?php

// 2024_01_01_000013_create_invoices_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('invoice_type', ['purchase', 'dispatch', 'return']);
            $table->morphs('invoiceable'); // Can be PO, Dispatch, Return
            $table->foreignId('vendor_id')->nullable()->constrained('vendors');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->string('file_path')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};