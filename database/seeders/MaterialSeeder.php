<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            [
                'name' => 'Steel Rod',
                'code' => 'SR001',
                'description' => 'High quality steel rod',
                'unit' => 'kg',
                'unit_price' => 50.00,
                'gst_rate' => 18.0,
                'category' => 'Metals',
                'is_active' => true,
            ],
            [
                'name' => 'Cement Bag',
                'code' => 'CB002',
                'description' => '50 kg cement bag',
                'unit' => 'bag',
                'unit_price' => 320.00,
                'gst_rate' => 12.0,
                'category' => 'Construction',
                'is_active' => true,
            ],
            [
                'name' => 'Paint Bucket',
                'code' => 'PB003',
                'description' => '20L white paint bucket',
                'unit' => 'bucket',
                'unit_price' => 1500.00,
                'gst_rate' => 28.0,
                'category' => 'Paint',
                'is_active' => true,
            ],
            [
                'name' => 'Wood Plank',
                'code' => 'WP004',
                'description' => 'Pine wood plank 2x4',
                'unit' => 'piece',
                'unit_price' => 200.00,
                'gst_rate' => 5.0,
                'category' => 'Wood',
                'is_active' => true,
            ],
            [
                'name' => 'Sand',
                'code' => 'SD005',
                'description' => 'Fine river sand',
                'unit' => 'ton',
                'unit_price' => 1000.00,
                'gst_rate' => 5.0,
                'category' => 'Construction',
                'is_active' => true,
            ],
        ];

        foreach ($materials as $material) {
            Material::firstOrCreate(
                ['code' => $material['code']], // condition to check uniqueness
                $material
            );
        }
    }
}
