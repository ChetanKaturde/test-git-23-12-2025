<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\Subscription;

echo "=== MINIMAL CONVERSION TEST ===\n";
echo "Testing with existing data\n\n";

// Step 1: Find existing quotation that can be converted
echo "Step 1: Finding existing quotation...\n";
$quotation = Quotation::where('status', '!=', 'converted')
    ->whereDoesntHave('invoice')
    ->with(['customer', 'items'])
    ->first();

if (!$quotation) {
    echo "❌ No convertible quotation found\n";
    return;
}

echo "✅ Found quotation: {$quotation->number} (Business: {$quotation->business_id})\n";
echo "   Status: {$quotation->status}\n";
echo "   Customer: {$quotation->customer->name}\n";
echo "   Total: ₹{$quotation->total}\n\n";

// Step 2: Find user for this business
echo "Step 2: Finding user for this business...\n";
$user = User::where('business_id', $quotation->business_id)
    ->where('role', 'admin')
    ->first();

if (!$user) {
    echo "❌ No admin user found for this business\n";
    return;
}

echo "✅ Found user: {$user->name} ({$user->email})\n";
echo "   Role: {$user->role}\n\n";

// Step 3: Login as this user
auth()->login($user);

// Step 4: Check subscription details
echo "Step 3: Checking subscription details...\n";
$business = $user->business;
$subscription = $user->currentSubscription();

echo "   Business: {$business->name}\n";
echo "   Has subscription: " . ($subscription ? 'YES' : 'NO') . "\n";

if ($subscription) {
    echo "   Subscription status: {$subscription->status}\n";
    echo "   Plan snapshot: " . json_encode($subscription->plan_snapshot) . "\n";
    
    // Check specific feature
    $features = $subscription->plan_snapshot['features'] ?? [];
    echo "   Available features: " . implode(', ', array_keys($features)) . "\n";
    
    // Check invoice management specifically
    $invoiceFeature = null;
    foreach ($features as $key => $feature) {
        if (stripos($key, 'invoice') !== false) {
            $invoiceFeature = $feature;
            echo "   Invoice feature found: {$key} = " . json_encode($feature) . "\n";
            break;
        }
    }
    
    if (!$invoiceFeature) {
        echo "   ❌ No invoice feature found in subscription\n";
    }
} else {
    echo "   ❌ No subscription found\n";
}

echo "\n";

// Step 5: Test permission methods
echo "Step 4: Testing permission methods...\n";
echo "   user->isAdmin(): " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
echo "   user->businessHasFeature('invoice_management'): " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
echo "   user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice'): " . ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";

if ($subscription) {
    echo "   subscription->isFeatureEnabled('invoice_management'): " . ($subscription->isFeatureEnabled('invoice_management') ? 'YES' : 'NO') . "\n";
    echo "   subscription->canUseFeature('invoice_management', 1): " . ($subscription->canUseFeature('invoice_management', 1) ? 'YES' : 'NO') . "\n";
}

echo "\n";

// Step 6: Test the exact controller logic
echo "Step 5: Testing exact controller logic...\n";

try {
    // Simulate the convertToInvoice method logic
    echo "   Checking business ownership...\n";
    if ($quotation->business_id !== $user->business_id) {
        echo "   ❌ Business ownership failed\n";
        return;
    }
    echo "   ✅ Business ownership passed\n";
    
    echo "   Checking permission...\n";
    if (!$user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice')) {
        echo "   ❌ Permission check failed\n";
        
        // Detailed permission analysis
        echo "   Detailed analysis:\n";
        echo "     Is superadmin: " . ($user->role === 'superadmin' ? 'YES' : 'NO') . "\n";
        echo "     Business has feature: " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "     Is admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
        
        return;
    }
    echo "   ✅ Permission check passed\n";
    
    echo "   Checking if already converted...\n";
    if ($quotation->status === 'converted' || Invoice::where('quotation_id', $quotation->id)->exists()) {
        echo "   ❌ Already converted\n";
        return;
    }
    echo "   ✅ Not converted yet\n";
    
    echo "   Checking subscription...\n";
    $subscription = $user->currentSubscription();
    if (!$subscription || !$subscription->isFeatureEnabled('invoice_management')) {
        echo "   ❌ Subscription check failed\n";
        if (!$subscription) {
            echo "     No subscription found\n";
        } else {
            echo "     Feature not enabled in subscription\n";
        }
        return;
    }
    echo "   ✅ Subscription check passed\n";
    
    echo "   Checking limits...\n";
    if (!$subscription->canUseFeature('invoice_management', 1)) {
        echo "   ❌ Limit check failed\n";
        return;
    }
    echo "   ✅ Limit check passed\n";
    
    echo "\n✅ ALL CHECKS PASSED! Conversion should work!\n";
    echo "This means the backend logic is working correctly.\n";
    echo "The issue is likely in the frontend/view layer.\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: {$e->getMessage()}\n";
}

echo "\n=== CONCLUSION ===\n";
echo "If all checks passed, the backend conversion logic works.\n";
echo "The issue is likely in the view layer or missing variables.\n";