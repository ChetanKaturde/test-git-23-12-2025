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
        Schema::table('inventory_batches', function (Blueprint $table) {
            // Add supplier_batch_number column
            $table->string('supplier_batch_number')->nullable()->after('batch_number');
            
            // Add quality_grade column
            $table->enum('quality_grade', ['A', 'B', 'C', 'Premium', 'Standard'])->nullable()->after('unit_price');
            
            // Add received_weight and received_quantity if they don't exist
            if (!Schema::hasColumn('inventory_batches', 'received_weight')) {
                $table->decimal('received_weight', 10, 3)->after('initial_weight');
            }
            
            if (!Schema::hasColumn('inventory_batches', 'received_quantity')) {
                $table->integer('received_quantity')->after('initial_quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_batches', function (Blueprint $table) {
            $table->dropColumn(['supplier_batch_number', 'quality_grade']);
            
            // Only drop if they exist
            if (Schema::hasColumn('inventory_batches', 'received_weight')) {
                $table->dropColumn('received_weight');
            }
            
            if (Schema::hasColumn('inventory_batches', 'received_quantity')) {
                $table->dropColumn('received_quantity');
            }
        });
    }
};