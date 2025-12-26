<?php

namespace App\Observers;

use App\Models\Business;
use App\Models\Material;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Machine;

class BusinessObserver
{
    /**
     * Handle the Business "created" event.
     */
    public function created(Business $business): void
    {
        $this->createSampleData($business);
    }

    /**
     * Create tier-appropriate sample data for new business
     */
    private function createSampleData(Business $business): void
    {
        if ($business->subscription_tier === 'billing_sales') {
            $this->createBillingSalesData($business);
        } else {
            $this->createFullErpData($business);
        }
    }

    /**
     * Create sample data for billing_sales tier
     */
    private function createBillingSalesData(Business $business): void
    {
        // Create 1 generic service item with unique code
        Material::create([
            'business_id' => $business->id,
            'name' => 'Consulting Hour',
            'item_type' => 'service',
            'unit' => 'hour',
            'unit_price' => 100.00,
            'is_active' => true,
            'code' => 'SRV' . str_pad($business->id, 3, '0', STR_PAD_LEFT) . '01',
        ]);

        // Create 1 sample customer
        Customer::create([
            'business_id' => $business->id,
            'name' => 'Sample Client',
            'type' => 'business',
            'phone' => '9876543210',
            'email' => 'client@example.com',
            'address' => '123 Business Street',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pin_code' => '400001',
            'is_active' => true,
        ]);
    }

    /**
     * Create sample data for full_erp tier
     */
    private function createFullErpData(Business $business): void
    {
        // Create 2 materials with unique codes
        Material::create([
            'business_id' => $business->id,
            'name' => 'Steel Rod',
            'item_type' => 'good',
            'unit' => 'kg',
            'unit_price' => 50.00,
            'is_active' => true,
            'code' => 'STL' . str_pad($business->id, 3, '0', STR_PAD_LEFT) . '01',
        ]);

        Material::create([
            'business_id' => $business->id,
            'name' => 'Aluminum Sheet',
            'item_type' => 'good',
            'unit' => 'piece',
            'unit_price' => 200.00,
            'is_active' => true,
            'code' => 'ALU' . str_pad($business->id, 3, '0', STR_PAD_LEFT) . '02',
        ]);

        // Create 1 vendor
        Vendor::create([
            'business_id' => $business->id,
            'name' => 'Sample Supplier',
            'email' => 'supplier@example.com',
            'phone' => '9876543210',
            'company_address' => '456 Supplier Lane, Industrial Area',
        ]);

        // Create 1 machine
        Machine::create([
            'business_id' => $business->id,
            'name' => 'Production Line 1',
            'code' => 'M' . str_pad($business->id, 4, '0', STR_PAD_LEFT) . '01',
            'type' => 'cnc',
            'status' => 'available',
            'location' => 'Factory Floor A',
        ]);

        // Create 1 customer
        Customer::create([
            'business_id' => $business->id,
            'name' => 'Walk-in Customer',
            'type' => 'individual',
            'phone' => '9876543211',
            'address' => '789 Customer Road',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'pin_code' => '110001',
            'is_active' => true,
        ]);
    }

    /**
     * Handle the Business "updated" event.
     */
    public function updated(Business $business): void
    {
        //
    }

    /**
     * Handle the Business "deleted" event.
     */
    public function deleted(Business $business): void
    {
        //
    }

    /**
     * Handle the Business "restored" event.
     */
    public function restored(Business $business): void
    {
        //
    }

    /**
     * Handle the Business "force deleted" event.
     */
    public function forceDeleted(Business $business): void
    {
        //
    }
}
