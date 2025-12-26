<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up()
    {
        // ---------- materials ----------
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (!Schema::hasColumn('materials', 'material_type')) {
                    $table->string('material_type')->default('raw_material')->after('category');
                }
                if (!Schema::hasColumn('materials', 'material_form')) {
                    $table->string('material_form')->nullable()->after('material_type');
                }
                if (!Schema::hasColumn('materials', 'grade')) {
                    $table->string('grade')->nullable()->after('material_form');
                }
                if (!Schema::hasColumn('materials', 'unit_of_stock')) {
                    $table->string('unit_of_stock')->default('kg')->after('unit');
                }
                if (!Schema::hasColumn('materials', 'unit_of_order')) {
                    $table->string('unit_of_order')->nullable()->after('unit_of_stock');
                }
                if (!Schema::hasColumn('materials', 'estimated_weight_per_piece')) {
                    $table->decimal('estimated_weight_per_piece', 10, 4)->nullable()->after('unit_of_order');
                }
            });

            if (
                Schema::hasColumn('materials', 'unit') &&
                Schema::hasColumn('materials', 'unit_of_stock')
            ) {
                DB::statement(
                    "UPDATE materials SET unit_of_stock = unit WHERE unit_of_stock = 'kg'"
                );
            }
        }

        // ---------- work_orders ----------
        if (Schema::hasTable('work_orders')) {
            Schema::table('work_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('work_orders', 'customer_id')) {
                    $table->unsignedBigInteger('customer_id')->nullable()->after('business_id');
                }
                if (!Schema::hasColumn('work_orders', 'quoted_rate')) {
                    $table->decimal('quoted_rate', 10, 2)->nullable()->after('quantity');
                }
            });

            // add FK ONLY if not exists
            $connection = Schema::getConnection();
            $sm = $connection->getDoctrineSchemaManager();
            $foreignKeys = $sm->listTableForeignKeys('work_orders');

            $hasCustomerFK = collect($foreignKeys)
                ->contains(fn ($fk) => in_array('customer_id', $fk->getLocalColumns()));

            if (!$hasCustomerFK && Schema::hasColumn('work_orders', 'customer_id')) {
                Schema::table('work_orders', function (Blueprint $table) {
                    $table->foreign('customer_id')
                        ->references('id')
                        ->on('customers')
                        ->nullOnDelete();
                });
            }
        }

        // ---------- inventory_batches ----------
        if (Schema::hasTable('inventory_batches')) {
            Schema::table('inventory_batches', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_batches', 'unit_price')) {
                    $table->decimal('unit_price', 10, 2)->nullable()->after('current_quantity');
                }
                if (!Schema::hasColumn('inventory_batches', 'warehouse_id')) {
                    $table->unsignedBigInteger('warehouse_id')->nullable()->after('material_id');
                }
            });
        }

        // ---------- material_consumptions ----------
        if (Schema::hasTable('material_consumptions')) {
            Schema::table('material_consumptions', function (Blueprint $table) {
                if (!Schema::hasColumn('material_consumptions', 'unit_cost')) {
                    $table->decimal('unit_cost', 10, 2)->nullable()->after('waste_quantity');
                }
                if (!Schema::hasColumn('material_consumptions', 'batch_id')) {
                    $table->unsignedBigInteger('batch_id')->nullable()->after('material_id');
                }
            });
        }

    }

    public function down()
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                foreach ([
                    'material_type',
                    'material_form',
                    'grade',
                    'unit_of_stock',
                    'unit_of_order',
                    'estimated_weight_per_piece'
                ] as $column) {
                    if (Schema::hasColumn('materials', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('work_orders')) {
            Schema::table('work_orders', function (Blueprint $table) {
                if (Schema::hasColumn('work_orders', 'customer_id')) {
                    $table->dropForeign(['customer_id']);
                    $table->dropColumn('customer_id');
                }
                if (Schema::hasColumn('work_orders', 'quoted_rate')) {
                    $table->dropColumn('quoted_rate');
                }
            });
        }

        if (Schema::hasTable('inventory_batches')) {
            Schema::table('inventory_batches', function (Blueprint $table) {
                foreach (['unit_price', 'warehouse_id'] as $column) {
                    if (Schema::hasColumn('inventory_batches', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('material_consumptions')) {
            Schema::table('material_consumptions', function (Blueprint $table) {
                foreach (['unit_cost', 'batch_id'] as $column) {
                    if (Schema::hasColumn('material_consumptions', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};