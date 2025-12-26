<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('restrict');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->string('number');
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'converted'])->default('draft');
            $table->date('valid_until');
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('business_id');
            $table->index('customer_id');
            $table->index('status');
            $table->unique(['business_id', 'number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotations');
    }
};