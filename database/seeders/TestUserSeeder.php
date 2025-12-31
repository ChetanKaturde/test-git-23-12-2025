<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Check if user already exists
        $existingUser = User::where('email', 'six@aol.com')->first();
        if ($existingUser) {
            $this->command->info("User already exists: {$existingUser->email}");
            $this->command->info("Business ID: {$existingUser->business_id}");
            $this->command->info("User ID: {$existingUser->id}");
            return;
        }

        // Create business first
        $business = Business::create([
            'name' => 'Test Business Six',
            'slug' => 'test-business-six-' . time(),
            'email' => 'six@aol.com',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'India',
            'currency' => 'INR',
            'financial_year_start' => '2024-04-01',
            'is_active' => true,
            'subscription_plan' => 'free'
        ]);

        // Create user
        $user = User::create([
            'name' => 'Test User Six',
            'email' => 'six@aol.com',
            'password' => Hash::make('somepassword'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'business_id' => $business->id
        ]);

        $this->command->info("User created: {$user->email} with business: {$business->name}");
        $this->command->info("Business ID: {$business->id}");
        $this->command->info("User ID: {$user->id}");
    }
}