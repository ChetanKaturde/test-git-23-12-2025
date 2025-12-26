<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Business;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        PurchaseOrderItem::truncate();
        PurchaseOrder::truncate();
        Material::truncate();
        Vendor::truncate();
        Business::truncate();
        
        // Create a sample business
        $business = Business::firstOrCreate([
            'slug' => 'sample-workshop'
        ], [
            'name' => 'Sample Manufacturing Workshop',
            'email' => 'info@sampleworkshop.com',
            'phone' => '9876543210',
            'address' => 'Industrial Area, Phase 2, Mumbai',
            'is_active' => true,
            'subscription_plan' => 'basic'
        ]);

        // Create sample materials
        $materials = [
            ['name' => 'Mild Steel Rod 12mm', 'code' => 'MSR-12', 'unit' => 'kg', 'category' => 'Raw Material'],
            ['name' => 'Aluminum Sheet 2mm', 'code' => 'ALS-2', 'unit' => 'sq.ft', 'category' => 'Raw Material'],
            ['name' => 'Welding Electrode', 'code' => 'WE-001', 'unit' => 'kg', 'category' => 'Consumable'],
            ['name' => 'Paint - Red Oxide', 'code' => 'PRO-001', 'unit' => 'liter', 'category' => 'Consumable'],
            ['name' => 'Bolts M8x20', 'code' => 'BLT-M8', 'unit' => 'piece', 'category' => 'Hardware'],
            ['name' => 'Cutting Blade', 'code' => 'CB-001', 'unit' => 'piece', 'category' => 'Tool'],
        ];

        foreach ($materials as $material) {
            Material::create(array_merge($material, [
                'unit_price' => rand(50, 500),
                'gst_rate' => 18.00,
                'is_active' => true
            ]));
        }

        // Create sample vendors
        $vendors = [
            [
                'name' => 'Steel Suppliers Ltd',
                'phone' => '9123456789',
                'email' => 'rajesh@steelsuppliers.com',
                'address' => 'Steel Market, Bhiwandi, Mumbai, Maharashtra'
            ],
            [
                'name' => 'Aluminum Works',
                'phone' => '9876543210',
                'email' => 'priya@aluminumworks.com',
                'address' => 'Industrial Estate, Pune, Maharashtra'
            ],
            [
                'name' => 'Hardware Hub',
                'phone' => '9988776655',
                'email' => 'amit@hardwarehub.com',
                'address' => 'Tool Market, Ahmedabad, Gujarat'
            ]
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }

        // Get created data
        $createdMaterials = Material::all();
        $createdVendors = Vendor::all();
        $adminUser = User::where('role', 'admin')->first();

        // Create sample purchase orders
        foreach ($createdVendors as $index => $vendor) {
            $po = PurchaseOrder::create([
                'po_number' => 'PO-2025-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'vendor_id' => $vendor->id,
                'po_date' => now()->subDays(rand(1, 30)),
                'status' => ['pending', 'approved', 'received'][rand(0, 2)],
                'total_amount' => 0,
                'notes' => 'Sample purchase order for testing'
            ]);

            // Add items to purchase order
            $selectedMaterials = $createdMaterials->random(rand(2, 4));
            $totalAmount = 0;

            foreach ($selectedMaterials as $material) {
                $quantity = rand(10, 100);
                $unitPrice = rand(50, 500);
                $totalPrice = $quantity * $unitPrice;
                $totalAmount += $totalPrice;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'item_name' => $material->name,
                    'description' => $material->category . ' - ' . $material->unit,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice
                ]);
            }

            // Update PO total
            $po->update(['total_amount' => $totalAmount]);
        }

        echo "Sample data created successfully!\n";
        echo "- Materials: " . Material::count() . "\n";
        echo "- Vendors: " . Vendor::count() . "\n";
        echo "- Purchase Orders: " . PurchaseOrder::count() . "\n";
    }
}