<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('batch_id')->constrained('inventory_batches');
            $table->enum('type', ['intake', 'dispatch', 'return', 'damage', 'adjustment']);
            $table->decimal('weight', 8, 3);
            $table->integer('quantity');
            $table->string('reference_number')->nullable(); // dispatch/return reference
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable(); // for additional data
            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
