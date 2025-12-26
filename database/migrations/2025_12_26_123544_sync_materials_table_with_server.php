<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                // Add item_type column (enum: 'good', 'service') after name
                if (!Schema::hasColumn('materials', 'item_type')) {
                    $table->enum('item_type', ['good', 'service'])
                        ->default('good')
                        ->after('name');
                }

                // Add hsn_code after item_type
                if (!Schema::hasColumn('materials', 'hsn_code')) {
                    $table->string('hsn_code')->nullable()->after('item_type');
                }

                // Add sku after hsn_code
                if (!Schema::hasColumn('materials', 'sku')) {
                    $table->string('sku')->nullable()->after('hsn_code');
                }

                // Add barcode after sku
                if (!Schema::hasColumn('materials', 'barcode')) {
                    $table->string('barcode')->nullable()->after('sku');
                }

                // Add dimensions (json) after barcode
                if (!Schema::hasColumn('materials', 'dimensions')) {
                    $table->json('dimensions')->nullable()->after('barcode');
                }

                // Reorder: code comes after dimensions (already exists, so we skip)

                // description already exists

                // Add material_type with default 'raw_material' after category
                if (!Schema::hasColumn('materials', 'material_type')) {
                    $table->string('material_type')
                        ->default('raw_material')
                        ->after('category');
                }

                // Add material_form after material_type
                if (!Schema::hasColumn('materials', 'material_form')) {
                    $table->string('material_form')->nullable()->after('material_type');
                }

                // Add grade after material_form
                if (!Schema::hasColumn('materials', 'grade')) {
                    $table->string('grade')->nullable()->after('material_form');
                }

                // unit_of_stock after grade (note: unit already exists, but this is separate)
                if (!Schema::hasColumn('materials', 'unit_of_stock')) {
                    $table->string('unit_of_stock')->default('kg')->after('grade');
                }

                // Add unit_of_order after unit_of_stock
                if (!Schema::hasColumn('materials', 'unit_of_order')) {
                    $table->string('unit_of_order')->nullable()->after('unit_of_stock');
                }

                // Add estimated_weight_per_piece after unit_of_order
                if (!Schema::hasColumn('materials', 'estimated_weight_per_piece')) {
                    $table->decimal('estimated_weight_per_piece', 10, 4)
                        ->nullable()
                        ->after('unit_of_order');
                }

                // unit_price, gst_rate, category, is_active, timestamps already exist

                // Add business_id at the end (before timestamps)
                if (!Schema::hasColumn('materials', 'business_id')) {
                    $table->unsignedBigInteger('business_id')->default(1)->after('is_active');
                    
                    // Add foreign key if businesses table exists
                    if (Schema::hasTable('businesses')) {
                        $table->foreign('business_id')
                            ->references('id')
                            ->on('businesses')
                            ->onDelete('cascade');
                    }
                }
            });

            // Copy unit to unit_of_stock if unit_of_stock is still 'kg'
            DB::statement(
                "UPDATE materials SET unit_of_stock = unit WHERE unit_of_stock = 'kg'"
            );
        }
    }

    public function down()
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                // Drop foreign key first if exists
                if (Schema::hasColumn('materials', 'business_id')) {
                    try {
                        $table->dropForeign(['business_id']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist
                    }
                }

                // Drop all added columns
                $columnsToRemove = [
                    'item_type',
                    'hsn_code',
                    'sku',
                    'barcode',
                    'dimensions',
                    'material_type',
                    'material_form',
                    'grade',
                    'unit_of_stock',
                    'unit_of_order',
                    'estimated_weight_per_piece',
                    'business_id'
                ];

                foreach ($columnsToRemove as $column) {
                    if (Schema::hasColumn('materials', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};