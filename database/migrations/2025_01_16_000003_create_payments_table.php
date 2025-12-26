<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('restrict');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('restrict');
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'upi', 'card', 'cheque', 'other']);
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('business_id');
            $table->index('invoice_id');
            $table->index('payment_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};