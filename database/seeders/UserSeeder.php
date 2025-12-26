<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@inventory.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@inventory.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Purchase Team User
        User::updateOrCreate(
            ['email' => 'purchase@inventory.com'],
            [
                'name' => 'Purchase User',
                'email' => 'purchase@inventory.com',
                'password' => Hash::make('purchase123'),
                'role' => 'purchase_team',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Inventory Manager User
        User::updateOrCreate(
            ['email' => 'inventory@inventory.com'],
            [
                'name' => 'Inventory User',
                'email' => 'inventory@inventory.com',
                'password' => Hash::make('inventory123'),
                'role' => 'inventory_manager',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Regular User
        User::updateOrCreate(
            ['email' => 'user@inventory.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@inventory.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Test users created successfully!');
        $this->command->line('Admin: admin@inventory.com / admin123');
        $this->command->line('Purchase: purchase@inventory.com / purchase123');
        $this->command->line('Inventory: inventory@inventory.com / inventory123');
        $this->command->line('User: user@inventory.com / user123');
    }
}