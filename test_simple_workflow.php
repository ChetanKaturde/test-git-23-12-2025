<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Invoice;
use App\Models\SalesRepresentative;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== SIMPLIFIED QUOTATION TO INVOICE TEST ===\n";
echo "Using existing business with subscription\n\n";

try {
    DB::beginTransaction();
    
    // Step 1: Find a business with active subscription
    echo "Step 1: Finding business with active subscription...\n";
    $businessWithSub = Business::whereHas('subscriptions', function($q) {
        $q->where('status', 'active');
    })->first();
    
    if (!$businessWithSub) {
        echo "❌ No business with active subscription found!\n";
        return;
    }
    
    echo "✅ Found business: {$businessWithSub->name} (ID: {$businessWithSub->id})\n";
    
    $subscription = $businessWithSub->subscriptions()->active()->first();
    echo "   Subscription status: {$subscription->status}\n";
    echo "   Features: " . json_encode($subscription->plan_snapshot['features'] ?? []) . "\n\n";
    
    // Step 2: Create new user for this business
    echo "Step 2: Creating new user for this business...\n";
    $user = User::create([
        'name' => 'Test User ' . time(),
        'email' => 'testuser' . time() . '@test.com',
        'password' => Hash::make('password123'),
        'plain_password' => 'password123',
        'role' => 'admin',
        'is_active' => true,
        'business_id' => $businessWithSub->id,
        'email_verified_at' => now(),
    ]);
    echo "✅ User created: {$user->name} ({$user->email})\n\n";
    
    // Step 3: Test user permissions
    echo "Step 3: Testing user permissions...\n";
    $hasSubscription = $user->currentSubscription();
    echo "   Has subscription: " . ($hasSubscription ? 'YES' : 'NO') . "\n";
    if ($hasSubscription) {
        echo "   Subscription status: {$hasSubscription->status}\n";
        echo "   Invoice management enabled: " . ($hasSubscription->isFeatureEnabled('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "   Can use invoice feature: " . ($hasSubscription->canUseFeature('invoice_management', 1) ? 'YES' : 'NO') . "\n";
    }
    echo "   Can access feature action: " . ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n\n";
    
    // Step 4: Create customer
    echo "Step 4: Creating customer...\n";
    $customer = Customer::create([
        'business_id' => $businessWithSub->id,
        'name' => 'Test Customer ' . time(),
        'email' => 'customer' . time() . '@test.com',
        'phone' => '9876543210',
        'address' => 'Customer Address',
        'city' => 'Test City',
        'state' => 'Test State',
        'pin_code' => '123456',
        'gstin' => 'TEST123456789',
        'is_active' => true,
    ]);
    echo "✅ Customer created: {$customer->name}\n\n";
    
    // Step 5: Create material
    echo "Step 5: Creating material...\n";
    $material = Material::create([
        'business_id' => $businessWithSub->id,
        'name' => 'Test Material ' . time(),
        'description' => 'Test material description',
        'sku' => 'TEST-' . time(),
        'unit' => 'pcs',
        'cost_price' => 100.00,
        'selling_price' => 150.00,
        'minimum_stock' => 10,
        'current_stock' => 100,
        'is_active' => true,
        'item_type' => 'raw_material',
        'hsn_code' => '1234',
    ]);
    echo "✅ Material created: {$material->name}\n\n";
    
    // Step 6: Create quotation
    echo "Step 6: Creating quotation...\n";
    auth()->login($user);
    
    $quotation = Quotation::create([
        'business_id' => $businessWithSub->id,
        'customer_id' => $customer->id,
        'number' => 'QUO-TEST-' . time(),
        'status' => 'draft',
        'valid_until' => now()->addDays(30),
        'notes' => 'Test quotation notes',
        'subtotal' => 1500.00,
        'tax_amount' => 270.00,
        'total' => 1770.00,
    ]);
    
    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'material_id' => $material->id,
        'description' => 'Test material item',
        'quantity' => 10,
        'unit' => 'pcs',
        'unit_price' => 150.00,
        'discount_percentage' => 0,
        'discount_amount' => 0,
        'tax_rate' => 18,
        'tax_amount' => 270.00,
        'total' => 1770.00,
    ]);
    
    echo "✅ Quotation created: {$quotation->number}\n\n";
    
    // Step 7: Test conversion logic
    echo "Step 7: Testing conversion logic...\n";
    $quotation->load(['customer', 'items']);
    
    // Check all conditions
    $ownershipCheck = ($quotation->business_id === $user->business_id);
    $permissionCheck = $user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice');
    $alreadyConverted = ($quotation->status === 'converted' || Invoice::where('quotation_id', $quotation->id)->exists());
    $subscription = $user->currentSubscription();
    $subscriptionCheck = ($subscription && $subscription->isFeatureEnabled('invoice_management'));
    $limitCheck = ($subscription && $subscription->canUseFeature('invoice_management', 1));
    
    echo "   Business ownership: " . ($ownershipCheck ? 'PASS' : 'FAIL') . "\n";
    echo "   Permission check: " . ($permissionCheck ? 'PASS' : 'FAIL') . "\n";
    echo "   Already converted: " . ($alreadyConverted ? 'FAIL' : 'PASS') . "\n";
    echo "   Subscription check: " . ($subscriptionCheck ? 'PASS' : 'FAIL') . "\n";
    echo "   Limit check: " . ($limitCheck ? 'PASS' : 'FAIL') . "\n\n";
    
    // Step 8: Attempt conversion
    echo "Step 8: Attempting conversion...\n";
    
    if (!$permissionCheck) {
        echo "❌ PERMISSION FAILED - Detailed analysis:\n";
        echo "   User role: {$user->role}\n";
        echo "   Is admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
        echo "   Is superadmin: " . ($user->role === 'superadmin' ? 'YES' : 'NO') . "\n";
        echo "   Business has feature: " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        
        if ($user->business) {
            echo "   Business subscription check: " . ($user->business->hasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        }
        
        echo "\n   Step-by-step canAccessFeatureAction analysis:\n";
        echo "   1. Is superadmin: " . ($user->role === 'superadmin' ? 'YES' : 'NO') . "\n";
        echo "   2. Business has feature: " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "   3. Is admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
        
    } elseif (!$subscriptionCheck) {
        echo "❌ SUBSCRIPTION FAILED\n";
        if ($subscription) {
            echo "   Subscription exists but feature not enabled\n";
            echo "   Plan snapshot: " . json_encode($subscription->plan_snapshot) . "\n";
        } else {
            echo "   No subscription found\n";
        }
    } elseif (!$limitCheck) {
        echo "❌ LIMIT CHECK FAILED\n";
    } else {
        echo "✅ All checks passed! Attempting conversion...\n";
        
        try {
            $invoice = Invoice::create([
                'business_id' => $quotation->business_id,
                'invoice_number' => 'INV-TEST-' . time(),
                'quotation_id' => $quotation->id,
                'customer_name' => $quotation->customer->name,
                'customer_email' => $quotation->customer->email ?? '',
                'customer_phone' => $quotation->customer->phone ?? '',
                'customer_address' => $quotation->customer->address ?? '',
                'customer_gstin' => $quotation->customer->gstin ?? '',
                'subtotal' => $quotation->subtotal,
                'tax_amount' => $quotation->tax_amount,
                'total_amount' => $quotation->total,
                'status' => 'draft',
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
            ]);
            
            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit ?? 'pcs',
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'tax_amount' => $item->tax_amount,
                    'total_price' => $item->total,
                ]);
            }
            
            $quotation->update(['status' => 'converted', 'converted_at' => now()]);
            $subscription->incrementFeatureUsage('invoice_management');
            
            echo "✅ SUCCESS! Invoice created: {$invoice->invoice_number}\n";
            
        } catch (Exception $e) {
            echo "❌ CONVERSION FAILED: {$e->getMessage()}\n";
        }
    }
    
    DB::rollBack();
    echo "\n✅ Test completed (data rolled back)\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "❌ TEST FAILED: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}\n";
    echo "Line: {$e->getLine()}\n";
}