<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optionally clear existing records
        Warehouse::truncate();

        Warehouse::create([
            'name' => 'Mumbai Central Warehouse',
            'address' => 'Plot 21, MIDC Industrial Area, Andheri East',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'contact_phone' => '9876543210',
            'contact_email' => 'mumbai@warehouse.in',
            'type' => 'distribution',
            'is_default' => true,
            'is_active' => true,
        ]);

        Warehouse::create([
            'name' => 'Delhi Storage Facility',
            'address' => 'Warehouse No. 12, Okhla Phase II',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'contact_phone' => '9123456789',
            'contact_email' => 'delhi@warehouse.in',
            'type' => 'main',
            'is_default' => false,
            'is_active' => true,
        ]);

        Warehouse::create([
            'name' => 'Bangalore Temp Hub',
            'address' => '#78, Electronic City Phase I',
            'city' => 'Bangalore',
            'state' => 'Karnataka',
            'contact_phone' => '9988776655',
            'contact_email' => 'bangalore@warehouse.in',
            'type' => 'temporary',
            'is_default' => false,
            'is_active' => false
        ]);
    }
}
