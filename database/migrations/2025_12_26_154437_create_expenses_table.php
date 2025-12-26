<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->enum('category', [
                'staff_payroll',
                'rent_property_expense',
                'electricity_utilities',
                'equipment_assets',
                'miscellaneous'
            ]);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->enum('payment_mode', ['cash', 'bank_transfer', 'upi', 'card', 'cheque', 'other']);
            $table->string('proof_file_path')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'expense_date']);
            $table->index(['business_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
