<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PaidPlanTestSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            ['plan' => 'starter', 'template' => 'manufacturing', 'email' => 'starter@test.com'],
            ['plan' => 'professional', 'template' => 'service', 'email' => 'pro@test.com'],
        ];

        foreach ($plans as $config) {
            // Create business
            $business = Business::create([
                'name' => ucfirst($config['plan']) . ' Test Business',
                'slug' => $config['plan'] . '-test-' . time(),
                'email' => $config['email'],
                'phone' => '9876543210',
                'address' => 'Test Address',
                'city' => 'Test City',
                'state' => 'Test State',
                'country' => 'India',
                'currency' => 'INR',
                'financial_year_start' => '2024-04-01',
                'is_active' => true,
                'subscription_plan' => 'free', // Will update via SQL
                'template' => $config['template']
            ]);

            // Update plan via raw SQL to bypass constraints
            DB::statement('UPDATE businesses SET subscription_plan = ? WHERE id = ?', [$config['plan'], $business->id]);

            // Create user
            $user = User::create([
                'name' => ucfirst($config['plan']) . ' Test User',
                'email' => $config['email'],
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'business_id' => $business->id
            ]);

            $this->command->info("Created {$config['plan']} plan test user: {$user->email}");
            $this->command->info("Business ID: {$business->id}");
            $this->command->info("Login: {$config['email']} / password");
            $this->command->line("---");
        }
    }
}