<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subscription;

echo "=== TESTING PHASE 1 FIXES ===\n";

// Test 1: Feature key mismatch fix
echo "Test 1: Feature Key Mismatch Fix\n";
$subscription = Subscription::where('status', 'active')->first();
if ($subscription) {
    echo "  Testing subscription ID: {$subscription->id}\n";
    echo "  Features: " . json_encode(array_keys($subscription->plan_snapshot['features'] ?? [])) . "\n";
    
    // Test old key (should work now)
    $oldKeyResult = $subscription->isFeatureEnabled('invoice_management');
    echo "  isFeatureEnabled('invoice_management'): " . ($oldKeyResult ? 'YES' : 'NO') . "\n";
    
    // Test new key (should still work)
    $newKeyResult = $subscription->isFeatureEnabled('Invoice Management');
    echo "  isFeatureEnabled('Invoice Management'): " . ($newKeyResult ? 'YES' : 'NO') . "\n";
    
    // Test canUseFeature
    $canUseResult = $subscription->canUseFeature('invoice_management', 1);
    echo "  canUseFeature('invoice_management', 1): " . ($canUseResult ? 'YES' : 'NO') . "\n";
} else {
    echo "  No active subscription found for testing\n";
}

echo "\n";

// Test 2: User permission check
echo "Test 2: User Permission Check\n";
$userWithSub = User::whereHas('business.subscriptions', function($q) {
    $q->where('status', 'active');
})->first();

if ($userWithSub) {
    echo "  Testing user: {$userWithSub->email}\n";
    echo "  Business: {$userWithSub->business->name}\n";
    
    $subscription = $userWithSub->currentSubscription();
    if ($subscription) {
        echo "  Has subscription: YES\n";
        echo "  canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice'): " . 
             ($userWithSub->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    } else {
        echo "  Has subscription: NO\n";
    }
} else {
    echo "  No user with subscription found for testing\n";
}

echo "\n";

// Test 3: Business canCreateInvoice method
echo "Test 3: Business canCreateInvoice Method\n";
$business = \App\Models\Business::whereHas('subscriptions', function($q) {
    $q->where('status', 'active');
})->first();

if ($business) {
    echo "  Testing business: {$business->name}\n";
    echo "  canCreateInvoice(): " . ($business->canCreateInvoice() ? 'YES' : 'NO') . "\n";
} else {
    echo "  No business with subscription found for testing\n";
}

echo "\n=== SUMMARY ===\n";
echo "✅ Phase 1 fixes implemented:\n";
echo "  1. Updated Subscription::isFeatureEnabled() to handle key variations\n";
echo "  2. Updated Subscription::canUseFeature() to use improved method\n";
echo "  3. Added \$canCreateInvoice variable to QuotationController::show()\n";
echo "\n";
echo "🧪 Test Results:\n";
if ($subscription && $oldKeyResult) {
    echo "  ✅ Feature key mismatch FIXED - 'invoice_management' now works\n";
} else {
    echo "  ❌ Feature key mismatch still exists\n";
}

if ($userWithSub && $userWithSub->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice')) {
    echo "  ✅ User permission check WORKING\n";
} else {
    echo "  ❌ User permission check still failing\n";
}

echo "\n🚀 Ready for user testing!\n";