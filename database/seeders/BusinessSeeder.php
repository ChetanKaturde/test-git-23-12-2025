<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        // Create default business for existing data
        Business::create([
            'name' => 'Default Workshop',
            'slug' => 'default',
            'email' => 'admin@monitorbizz.com',
            'is_active' => true,
            'subscription_plan' => 'premium',
            'subscription_expires_at' => null, // Never expires
        ]);

        // Create sample businesses
        Business::create([
            'name' => 'Raj Metal Works',
            'slug' => 'raj-metal',
            'email' => 'raj@rajmetal.com',
            'phone' => '+91 98765 43210',
            'address' => 'Industrial Area, Mumbai',
            'is_active' => true,
            'subscription_plan' => 'basic',
        ]);

        Business::create([
            'name' => 'Priya Furniture',
            'slug' => 'priya-furniture',
            'email' => 'priya@priyafurniture.com',
            'phone' => '+91 87654 32109',
            'address' => 'Furniture Hub, Bangalore',
            'is_active' => true,
            'subscription_plan' => 'free',
        ]);
    }
}