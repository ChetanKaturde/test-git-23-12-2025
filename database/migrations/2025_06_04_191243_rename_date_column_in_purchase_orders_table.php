<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Check if 'date' column exists before renaming
    if (Schema::hasColumn('purchase_orders', 'date')) {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->renameColumn('date', 'po_date');
        });
    }
}

public function down()
{
    // Check if 'po_date' column exists before renaming back
    if (Schema::hasColumn('purchase_orders', 'po_date')) {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->renameColumn('po_date', 'date');
        });
    }
}

};
