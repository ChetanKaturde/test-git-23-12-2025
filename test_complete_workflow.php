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
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== QUOTATION TO INVOICE CONVERSION TEST ===\n";
echo "Testing complete workflow from user creation to invoice conversion\n\n";

try {
    DB::beginTransaction();
    
    // Step 1: Verify sales representative
    echo "Step 1: Verifying sales representative code 'MNBZDKSKUFZA'...\n";
    $salesRep = SalesRepresentative::where('representative_id', 'MNBZDKSKUFZA')->first();
    if (!$salesRep) {
        echo "❌ Sales representative not found!\n";
        return;
    }
    echo "✅ Sales representative found: {$salesRep->full_name} (Status: {$salesRep->status})\n\n";
    
    // Step 2: Create new business
    echo "Step 2: Creating new business...\n";
    $business = Business::create([
        'name' => 'Test Business ' . time(),
        'legal_name' => 'Test Business Legal ' . time(),
        'slug' => 'test-business-' . time(),
        'email' => 'test' . time() . '@testbusiness.com',
        'phone' => '9876543210',
        'address' => 'Test Address',
        'is_active' => true,
        'subscription_plan' => 'free',
        'subscription_tier' => 'full_erp',
        'sales_representative_id' => $salesRep->id,
        'currency' => 'INR',
        'financial_year_start' => '2025-04-01',
    ]);
    echo "✅ Business created: {$business->name} (ID: {$business->id})\n\n";
    
    // Step 3: Create subscription for the business
    echo "Step 3: Creating subscription for the business...\n";
    $plan = SubscriptionPlan::where('name', 'Professional')->first();
    if (!$plan) {
        $plan = SubscriptionPlan::first(); // Get any plan
    }
    
    $subscription = Subscription::create([
        'business_id' => $business->id,
        'plan_id' => $plan->id,
        'user_count' => 5,
        'start_date' => now(),
        'end_date' => now()->addYear(),
        'status' => 'active',
        'plan_snapshot' => [
            'features' => [
                'quotation_management' => ['enabled' => true, 'limit' => 50],
                'invoice_management' => ['enabled' => true, 'limit' => 50],
                'customer_management' => ['enabled' => true, 'limit' => 100],
                'commodity_management' => ['enabled' => true, 'limit' => null],
            ]
        ],
        'sales_representative_id' => $salesRep->id,
        'amount' => 999.00,
        'type' => 'monthly',
    ]);
    echo "✅ Subscription created: Plan {$plan->name} (Status: {$subscription->status})\n\n";
    
    // Step 4: Create new user
    echo "Step 4: Creating new user...\n";
    $user = User::create([
        'name' => 'Test User ' . time(),
        'email' => 'testuser' . time() . '@test.com',
        'password' => Hash::make('password123'),
        'plain_password' => 'password123',
        'role' => 'admin',
        'is_active' => true,
        'business_id' => $business->id,
        'email_verified_at' => now(),
    ]);
    echo "✅ User created: {$user->name} ({$user->email})\n";
    echo "   Role: {$user->role} | Business ID: {$user->business_id}\n\n";
    
    // Step 5: Test user permissions
    echo "Step 5: Testing user permissions...\n";
    $hasSubscription = $user->currentSubscription();
    echo "   Has subscription: " . ($hasSubscription ? 'YES' : 'NO') . "\n";
    if ($hasSubscription) {
        echo "   Subscription status: {$hasSubscription->status}\n";
        echo "   Invoice management enabled: " . ($hasSubscription->isFeatureEnabled('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "   Can use invoice feature: " . ($hasSubscription->canUseFeature('invoice_management', 1) ? 'YES' : 'NO') . "\n";
    }
    echo "   Can access feature action: " . ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n\n";
    
    // Step 6: Create customer
    echo "Step 6: Creating customer...\n";
    $customer = Customer::create([
        'business_id' => $business->id,
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
    echo "✅ Customer created: {$customer->name} (ID: {$customer->id})\n\n";
    
    // Step 7: Create material/commodity
    echo "Step 7: Creating material/commodity...\n";
    $material = Material::create([
        'business_id' => $business->id,
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
    echo "✅ Material created: {$material->name} (SKU: {$material->sku})\n\n";
    
    // Step 8: Create quotation
    echo "Step 8: Creating quotation...\n";
    
    // Simulate auth user for quotation creation
    auth()->login($user);
    
    $quotation = Quotation::create([
        'business_id' => $business->id,
        'customer_id' => $customer->id,
        'number' => 'QUO-TEST-' . time(),
        'status' => 'draft',
        'valid_until' => now()->addDays(30),
        'notes' => 'Test quotation notes',
        'subtotal' => 1500.00,
        'tax_amount' => 270.00,
        'total' => 1770.00,
    ]);
    
    // Create quotation item
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
    
    echo "✅ Quotation created: {$quotation->number} (ID: {$quotation->id})\n";
    echo "   Customer: {$customer->name}\n";
    echo "   Total: ₹{$quotation->total}\n";
    echo "   Items: 1\n\n";
    
    // Step 9: Test conversion logic step by step
    echo "Step 9: Testing conversion logic step by step...\n";
    
    // Load quotation with relationships
    $quotation->load(['customer', 'items']);
    
    // Check business ownership
    $ownershipCheck = ($quotation->business_id === $user->business_id);
    echo "   Business ownership check: " . ($ownershipCheck ? 'PASS' : 'FAIL') . "\n";
    
    // Check permission
    $permissionCheck = $user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice');
    echo "   Permission check: " . ($permissionCheck ? 'PASS' : 'FAIL') . "\n";
    
    // Check if already converted
    $alreadyConverted = ($quotation->status === 'converted' || Invoice::where('quotation_id', $quotation->id)->exists());
    echo "   Already converted check: " . ($alreadyConverted ? 'FAIL (already converted)' : 'PASS') . "\n";
    
    // Check subscription
    $subscription = $user->currentSubscription();
    $subscriptionCheck = ($subscription && $subscription->isFeatureEnabled('invoice_management'));
    echo "   Subscription check: " . ($subscriptionCheck ? 'PASS' : 'FAIL') . "\n";
    
    // Check limits
    $limitCheck = ($subscription && $subscription->canUseFeature('invoice_management', 1));
    echo "   Limit check: " . ($limitCheck ? 'PASS' : 'FAIL') . "\n\n";
    
    // Step 10: Attempt conversion
    echo "Step 10: Attempting quotation to invoice conversion...\n";
    
    if (!$ownershipCheck) {
        echo "❌ FAILED: Business ownership check failed\n";
    } elseif (!$permissionCheck) {
        echo "❌ FAILED: Permission check failed\n";
        echo "   User role: {$user->role}\n";
        echo "   Is admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
        echo "   Business has feature: " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
    } elseif ($alreadyConverted) {
        echo "❌ FAILED: Quotation already converted\n";
    } elseif (!$subscriptionCheck) {
        echo "❌ FAILED: Subscription check failed\n";
    } elseif (!$limitCheck) {
        echo "❌ FAILED: Limit check failed\n";
    } else {
        echo "✅ All checks passed! Proceeding with conversion...\n";
        
        try {
            // Create invoice
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
            
            // Copy items
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
            
            // Mark quotation as converted
            $quotation->update(['status' => 'converted', 'converted_at' => now()]);
            
            // Increment usage
            $subscription->incrementFeatureUsage('invoice_management');
            
            echo "✅ SUCCESS! Invoice created successfully!\n";
            echo "   Invoice Number: {$invoice->invoice_number}\n";
            echo "   Invoice ID: {$invoice->id}\n";
            echo "   Total Amount: ₹{$invoice->total_amount}\n";
            echo "   Quotation Status: {$quotation->fresh()->status}\n\n";
            
        } catch (Exception $e) {
            echo "❌ FAILED: Exception during conversion: {$e->getMessage()}\n";
            echo "   File: {$e->getFile()}\n";
            echo "   Line: {$e->getLine()}\n\n";
        }
    }
    
    // Step 11: Final verification
    echo "Step 11: Final verification...\n";
    $finalQuotation = Quotation::find($quotation->id);
    $createdInvoice = Invoice::where('quotation_id', $quotation->id)->first();
    
    echo "   Quotation status: {$finalQuotation->status}\n";
    echo "   Invoice created: " . ($createdInvoice ? 'YES' : 'NO') . "\n";
    if ($createdInvoice) {
        echo "   Invoice number: {$createdInvoice->invoice_number}\n";
        echo "   Invoice status: {$createdInvoice->status}\n";
    }
    
    echo "\n=== TEST SUMMARY ===\n";
    echo "Business: {$business->name} (ID: {$business->id})\n";
    echo "User: {$user->name} ({$user->email})\n";
    echo "Subscription: " . ($subscription ? 'Active' : 'None') . "\n";
    echo "Quotation: {$quotation->number}\n";
    echo "Conversion: " . ($createdInvoice ? 'SUCCESS' : 'FAILED') . "\n";
    
    DB::rollBack(); // Don't save test data
    echo "\n✅ Test completed (data rolled back)\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "❌ TEST FAILED: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}\n";
    echo "Line: {$e->getLine()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n";
}