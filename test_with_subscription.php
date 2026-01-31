<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== TESTING WITH SUBSCRIPTION BUSINESS ===\n";

try {
    DB::beginTransaction();
    
    // Step 1: Find business with active subscription
    echo "Step 1: Finding business with active subscription...\n";
    $businessWithSub = Business::whereHas('subscriptions', function($q) {
        $q->where('status', 'active');
    })->first();
    
    if (!$businessWithSub) {
        echo "❌ No business with subscription found\n";
        return;
    }
    
    echo "✅ Found business: {$businessWithSub->name} (ID: {$businessWithSub->id})\n";
    
    $subscription = $businessWithSub->subscriptions()->active()->first();
    echo "   Subscription: {$subscription->status}\n";
    echo "   Features: " . json_encode($subscription->plan_snapshot['features'] ?? []) . "\n\n";
    
    // Step 2: Create or find user for this business
    echo "Step 2: Creating user for this business...\n";
    $user = User::create([
        'name' => 'Test Admin User',
        'email' => 'testadmin' . time() . '@test.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
        'business_id' => $businessWithSub->id,
        'email_verified_at' => now(),
    ]);
    echo "✅ User created: {$user->name} ({$user->email})\n\n";
    
    // Step 3: Create customer for this business
    echo "Step 3: Creating customer...\n";
    $customer = Customer::firstOrCreate([
        'business_id' => $businessWithSub->id,
        'email' => 'testcustomer@test.com'
    ], [
        'name' => 'Test Customer',
        'phone' => '9876543210',
        'is_active' => true,
    ]);
    echo "✅ Customer: {$customer->name}\n\n";
    
    // Step 4: Create simple quotation
    echo "Step 4: Creating quotation...\n";
    auth()->login($user);
    
    $quotation = Quotation::create([
        'business_id' => $businessWithSub->id,
        'customer_id' => $customer->id,
        'number' => 'QUO-TEST-' . time(),
        'status' => 'draft',
        'valid_until' => now()->addDays(30),
        'subtotal' => 1000.00,
        'tax_amount' => 180.00,
        'total' => 1180.00,
    ]);
    
    // Create a simple quotation item (without material reference)
    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'description' => 'Test Service',
        'quantity' => 1,
        'unit' => 'service',
        'unit_price' => 1000.00,
        'tax_rate' => 18,
        'tax_amount' => 180.00,
        'total' => 1180.00,
    ]);
    
    echo "✅ Quotation created: {$quotation->number}\n\n";
    
    // Step 5: Test permissions
    echo "Step 5: Testing permissions...\n";
    echo "   User role: {$user->role}\n";
    echo "   Is admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "   Has subscription: " . ($user->currentSubscription() ? 'YES' : 'NO') . "\n";
    
    $subscription = $user->currentSubscription();
    if ($subscription) {
        echo "   Subscription status: {$subscription->status}\n";
        
        // Check invoice management feature
        $features = $subscription->plan_snapshot['features'] ?? [];
        $invoiceFeatureKey = null;
        foreach ($features as $key => $feature) {
            if (stripos($key, 'invoice') !== false) {
                $invoiceFeatureKey = $key;
                echo "   Invoice feature: {$key} = " . json_encode($feature) . "\n";
                break;
            }
        }
        
        if ($invoiceFeatureKey) {
            echo "   Feature enabled: " . ($subscription->isFeatureEnabled($invoiceFeatureKey) ? 'YES' : 'NO') . "\n";
            echo "   Can use feature: " . ($subscription->canUseFeature($invoiceFeatureKey, 1) ? 'YES' : 'NO') . "\n";
        }
    }
    
    echo "   Business has feature: " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
    echo "   Can access feature action: " . ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n\n";
    
    // Step 6: Test conversion
    echo "Step 6: Testing conversion...\n";
    $quotation->load(['customer', 'items']);
    
    // Check all conditions step by step
    echo "   Business ownership: " . (($quotation->business_id === $user->business_id) ? 'PASS' : 'FAIL') . "\n";
    echo "   Permission check: " . ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'PASS' : 'FAIL') . "\n";
    echo "   Already converted: " . (($quotation->status === 'converted' || Invoice::where('quotation_id', $quotation->id)->exists()) ? 'FAIL' : 'PASS') . "\n";
    
    $subscription = $user->currentSubscription();
    echo "   Has subscription: " . ($subscription ? 'PASS' : 'FAIL') . "\n";
    
    if ($subscription) {
        // Try different feature key variations
        $featureEnabled = false;
        $featureKeys = ['invoice_management', 'Invoice Management', 'invoices', 'Invoices'];
        
        foreach ($featureKeys as $key) {
            if ($subscription->isFeatureEnabled($key)) {
                echo "   Feature '{$key}' enabled: YES\n";
                $featureEnabled = true;
                break;
            }
        }
        
        if (!$featureEnabled) {
            echo "   No invoice feature enabled in subscription\n";
        }
        
        echo "   Can use feature: " . ($subscription->canUseFeature('invoice_management', 1) ? 'PASS' : 'FAIL') . "\n";
    }
    
    // Final test
    if ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice')) {
        echo "\n✅ ALL CHECKS PASSED! Attempting conversion...\n";
        
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
                    'unit' => $item->unit ?? 'service',
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'tax_amount' => $item->tax_amount,
                    'total_price' => $item->total,
                ]);
            }
            
            $quotation->update(['status' => 'converted', 'converted_at' => now()]);
            
            echo "✅ SUCCESS! Invoice created: {$invoice->invoice_number}\n";
            echo "   Invoice ID: {$invoice->id}\n";
            echo "   Total: ₹{$invoice->total_amount}\n";
            echo "   Quotation status: {$quotation->fresh()->status}\n";
            
        } catch (Exception $e) {
            echo "❌ Conversion failed: {$e->getMessage()}\n";
        }
    } else {
        echo "\n❌ PERMISSION CHECK FAILED - Backend would block this conversion\n";
    }
    
    DB::rollBack();
    echo "\n✅ Test completed (data rolled back)\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "❌ Test failed: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}\n";
    echo "Line: {$e->getLine()}\n";
}