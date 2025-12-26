<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
//use Exception;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    // Check if 'notes' column doesn't exist before adding
    if (!Schema::hasColumn('purchase_orders', 'notes')) {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('po_date'); // Add notes column after po_date
        });
    }
}

public function down()
{
    try {
        if (Schema::hasColumn('purchase_orders', 'notes')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    } catch (Exception $e) {
        Log::warning('Failed to drop notes column: ' . $e->getMessage());
    }
}

};
