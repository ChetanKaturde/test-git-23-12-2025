<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_order_items', 'material_id')) {
                $table->foreignId('material_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('purchase_order_items', 'weight')) {
                $table->decimal('weight', 8, 3)->nullable();
            }
            if (!Schema::hasColumn('purchase_order_items', 'rate')) {
                $table->decimal('rate', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('purchase_order_items', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('purchase_order_items', 'total')) {
                $table->decimal('total', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_order_items', 'material_id')) {
                $table->dropConstrainedForeignId('material_id');
            }
            if (Schema::hasColumn('purchase_order_items', 'weight')) {
                $table->dropColumn('weight');
            }
            if (Schema::hasColumn('purchase_order_items', 'rate')) {
                $table->dropColumn('rate');
            }
            if (Schema::hasColumn('purchase_order_items', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('purchase_order_items', 'total')) {
                $table->dropColumn('total');
            }
        });
    }
};
