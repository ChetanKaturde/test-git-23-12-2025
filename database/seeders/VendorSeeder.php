<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'Alpha Supplies',
                'email' => 'contact@alphasupplies.com',
                'phone' => '123-456-7890',
                'address' => '123 Alpha Street, Cityville',
            ],
            [
                'name' => 'Beta Traders',
                'email' => 'info@betatraders.com',
                'phone' => '234-567-8901',
                'address' => '456 Beta Avenue, Townsville',
            ],
            [
                'name' => 'Gamma Wholesale',
                'email' => 'sales@gammawholesale.com',
                'phone' => '345-678-9012',
                'address' => '789 Gamma Blvd, Villagetown',
            ],
            [
                'name' => 'Delta Distributors',
                'email' => 'support@deltadistributors.com',
                'phone' => '456-789-0123',
                'address' => '101 Delta Road, Hamletburg',
            ],
            [
                'name' => 'Epsilon Partners',
                'email' => 'service@epsilonpartners.com',
                'phone' => '567-890-1234',
                'address' => '202 Epsilon Lane, Metrocity',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::firstOrCreate(
                ['email' => $vendor['email']], // prevent duplicates based on email
                $vendor
            );
        }
    }
}
