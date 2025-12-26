<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's seed Vendors (IDs 10-14)
        $this->seedVendors();
        
        // Then, seed Materials (IDs 6-10)
        $this->seedMaterials();
        
        // Finally, seed Purchase Orders with Items
        $this->seedPurchaseOrders();
    }

    private function seedVendors(): void
    {
        // First, ensure we have at least 5 vendors from your existing seeder
        // Then add 5 more to get IDs 6-10 (which will be used as 10-14 in our logic)
        
        $additionalVendors = [
            [
                'name' => 'Omega Construction Supplies',
                'email' => 'contact@omegaconstruction.com',
                'phone' => '9876543210',
                'address' => '123 Industrial Area, Mumbai, Maharashtra',
            ],
            [
                'name' => 'Zeta Materials Ltd',
                'email' => 'info@zetamaterials.com',
                'phone' => '9876543211',
                'address' => '456 Business Park, Pune, Maharashtra',
            ],
            [
                'name' => 'Theta Industries',
                'email' => 'sales@thetaindustries.com',
                'phone' => '9876543212',
                'address' => '789 Manufacturing Hub, Nashik, Maharashtra',
            ],
            [
                'name' => 'Kappa Traders',
                'email' => 'orders@kappatraders.com',
                'phone' => '9876543213',
                'address' => '321 Trade Center, Aurangabad, Maharashtra',
            ],
            [
                'name' => 'Lambda Supply Chain',
                'email' => 'procurement@lambdachain.com',
                'phone' => '9876543214',
                'address' => '654 Logistics Park, Nagpur, Maharashtra',
            ],
        ];

        foreach ($additionalVendors as $vendor) {
            Vendor::firstOrCreate(
                ['email' => $vendor['email']], // prevent duplicates based on email
                $vendor
            );
        }
    }

    private function seedMaterials(): void
    {
        // Add one more material to get ID 6 (since you already have 5 materials with IDs 1-5)
        $additionalMaterial = [
            'name' => 'Electrical Wire',
            'code' => 'EW006',
            'description' => '2.5mm copper electrical wire',
            'unit' => 'meter',
            'unit_price' => 15.00,
            'gst_rate' => 18.0,
            'category' => 'Electrical',
            'is_active' => true,
        ];

        Material::firstOrCreate(
            ['code' => $additionalMaterial['code']], // condition to check uniqueness
            $additionalMaterial
        );
    }

    private function seedPurchaseOrders(): void
    {
        // Get actual vendor and material IDs from database
        $vendorIds = Vendor::pluck('id')->toArray();
        $materialIds = Material::pluck('id')->toArray();
        
        // Ensure we have enough vendors and materials
        if (count($vendorIds) < 5) {
            $this->command->error('Not enough vendors in database. Please run VendorSeeder first.');
            return;
        }
        
        if (count($materialIds) < 5) {
            $this->command->error('Not enough materials in database. Please run MaterialSeeder first.');
            return;
        }
        
        // Use the last 5 vendor IDs and first 6 material IDs
        $useVendorIds = array_slice($vendorIds, -5, 5);
        $useMaterialIds = array_slice($materialIds, 0, 6);
        
        $purchaseOrders = [];
        $purchaseOrderItems = [];
        
        // Sample data for different scenarios
        $statuses = ['pending', 'approved', 'ordered', 'received', 'cancelled'];
        $paymentModes = ['cash', 'bank_transfer', 'cheque', 'credit'];
        $creditDaysOptions = [0, 15, 30, 45, 60];
        
        for ($i = 1; $i <= 15; $i++) {
            $vendorId = $useVendorIds[array_rand($useVendorIds)];
            $poDate = Carbon::now()->subDays(rand(1, 90));
            $expectedDelivery = $poDate->copy()->addDays(rand(7, 30));
            $status = $statuses[array_rand($statuses)];
            $paymentMode = $paymentModes[array_rand($paymentModes)];
            $creditDays = $paymentMode === 'credit' ? $creditDaysOptions[array_rand($creditDaysOptions)] : null;
            
            $purchaseOrder = [
                'po_number' => $this->generatePoNumber($i),
                'vendor_id' => $vendorId,
                'po_date' => $poDate->format('Y-m-d'),
                'expected_delivery' => $expectedDelivery->format('Y-m-d'),
                'payment_mode' => $paymentMode,
                'credit_days' => $creditDays,
                'supplier_contact' => '98765432' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'shipping_cost' => rand(0, 500),
                'notes' => "Purchase order notes for PO #{$i}",
                'status' => $status,
                'total_amount' => 0, // Will be calculated after items
                'gst_amount' => 0, // Will be calculated after items
                'final_amount' => 0, // Will be calculated after items
                'created_at' => $poDate,
                'updated_at' => $poDate,
            ];
            
            $purchaseOrders[] = $purchaseOrder;
        }
        
        // Insert purchase orders first
        foreach ($purchaseOrders as $index => $po) {
            $purchaseOrderId = DB::table('purchase_orders')->insertGetId($po);
            
            // Generate 2-5 items per purchase order
            $itemCount = rand(2, 5);
            $totalAmount = 0;
            $totalGstAmount = 0;
            
            for ($j = 1; $j <= $itemCount; $j++) {
                $materialId = $useMaterialIds[array_rand($useMaterialIds)];
                $quantity = rand(1, 50);
                $weight = rand(1, 10);
                $unitPrice = $this->getMaterialPrice($materialId);
                $gstRate = $this->getMaterialGstRate($materialId);
                
                $subtotal = $weight * $quantity * $unitPrice;
                $gstAmount = ($subtotal * $gstRate) / 100;
                $total = $subtotal + $gstAmount;
                
                $totalAmount += $subtotal;
                $totalGstAmount += $gstAmount;
                
                $item = [
                    'purchase_order_id' => $purchaseOrderId,
                    'material_id' => $materialId,
                    'item_name' => $this->getMaterialName($materialId),
                    'description' => "Item description for material ID {$materialId}",
                    'quantity' => $quantity,
                    'weight' => $weight,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'gst_rate' => $gstRate,
                    'gst_amount' => $gstAmount,
                    'total' => $total,
                    'total_price' => $total,
                    'net_price' => $total,
                    'batch_number' => 'BATCH-' . date('Y') . '-' . str_pad($purchaseOrderId, 4, '0', STR_PAD_LEFT) . '-' . $j,
                    'expiry_date' => $materialId == 3 ? Carbon::now()->addYears(2)->format('Y-m-d') : null, // Paint has expiry
                    'created_at' => $purchaseOrders[$index]['created_at'],
                    'updated_at' => $purchaseOrders[$index]['updated_at'],
                ];
                
                $purchaseOrderItems[] = $item;
            }
            
            // Update purchase order totals
            $finalAmount = $totalAmount + $totalGstAmount + $purchaseOrders[$index]['shipping_cost'];
            
            DB::table('purchase_orders')
                ->where('id', $purchaseOrderId)
                ->update([
                    'total_amount' => $totalAmount,
                    'gst_amount' => $totalGstAmount,
                    'final_amount' => $finalAmount,
                ]);
        }
        
        // Insert all purchase order items
        DB::table('purchase_order_items')->insert($purchaseOrderItems);
        
        $this->command->info('Purchase Orders seeded successfully!');
        $this->command->info('- Created ' . count($purchaseOrders) . ' purchase orders');
        $this->command->info('- Created ' . count($purchaseOrderItems) . ' purchase order items');
        $this->command->info('- Used vendor IDs: ' . implode(', ', $useVendorIds));
        $this->command->info('- Used material IDs: ' . implode(', ', $useMaterialIds));
    }

    private function generatePoNumber($sequence): string
    {
        $year = date('Y');
        return 'PO' . $year . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    private function getMaterialPrice($materialId): float
    {
        // Get actual price from database
        $material = Material::find($materialId);
        return $material ? $material->unit_price : 100.00;
    }

    private function getMaterialGstRate($materialId): float
    {
        // Get actual GST rate from database
        $material = Material::find($materialId);
        return $material ? $material->gst_rate : 18.00;
    }

    private function getMaterialName($materialId): string
    {
        // Get actual name from database
        $material = Material::find($materialId);
        return $material ? $material->name : 'Unknown Material';
    }
}