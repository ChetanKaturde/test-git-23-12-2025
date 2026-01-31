<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Quotation;

echo "=== TESTING USER: amazonq@asd.cm ===\n";

$user = User::where('email', 'amazonq@asd.cm')->first();
if (!$user) {
    echo "❌ User not found!\n";
    exit;
}

echo "✅ User found: {$user->name} ({$user->email})\n";
echo "   Role: {$user->role}\n";
echo "   Business ID: {$user->business_id}\n";

if ($user->business) {
    echo "   Business: {$user->business->name}\n";
    echo "   Business Plan: {$user->business->subscription_plan}\n";
} else {
    echo "   ❌ No business found!\n";
    exit;
}

// Check subscription
$subscription = $user->currentSubscription();
echo "\n=== SUBSCRIPTION CHECK ===\n";
echo "Has subscription: " . ($subscription ? 'YES' : 'NO') . "\n";

if ($subscription) {
    echo "Subscription ID: {$subscription->id}\n";
    echo "Status: {$subscription->status}\n";
    echo "Plan ID: {$subscription->plan_id}\n";
    echo "Features: " . json_encode($subscription->plan_snapshot['features'] ?? []) . "\n";
    
    // Test feature checks
    echo "\n=== FEATURE CHECKS ===\n";
    echo "isFeatureEnabled('invoice_management'): " . ($subscription->isFeatureEnabled('invoice_management') ? 'YES' : 'NO') . "\n";
    echo "isFeatureEnabled('Invoice Management'): " . ($subscription->isFeatureEnabled('Invoice Management') ? 'YES' : 'NO') . "\n";
    echo "canUseFeature('invoice_management', 1): " . ($subscription->canUseFeature('invoice_management', 1) ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ No active subscription found\n";
}

// Check user permissions
echo "\n=== USER PERMISSION CHECKS ===\n";
echo "isAdmin(): " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
echo "businessHasFeature('invoice_management'): " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
echo "canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice'): " . ($user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";

// Check if user has quotations
echo "\n=== QUOTATION CHECK ===\n";
$quotations = Quotation::where('business_id', $user->business_id)
    ->where('status', '!=', 'converted')
    ->get();

echo "Available quotations for conversion: {$quotations->count()}\n";
foreach ($quotations as $quotation) {
    echo "  - {$quotation->number} (Status: {$quotation->status})\n";
}

// Simulate the exact conversion check
if ($quotations->count() > 0) {
    $testQuotation = $quotations->first();
    echo "\n=== CONVERSION SIMULATION ===\n";
    echo "Testing quotation: {$testQuotation->number}\n";
    
    // Step 1: Permission check
    $permissionCheck = $user->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice');
    echo "1. Permission check: " . ($permissionCheck ? 'PASS' : 'FAIL') . "\n";
    
    if (!$permissionCheck) {
        echo "   - Is admin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
        echo "   - Business has feature: " . ($user->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "   - Has subscription: " . ($user->currentSubscription() ? 'YES' : 'NO') . "\n";
        if ($user->currentSubscription()) {
            echo "   - Feature enabled: " . ($user->currentSubscription()->isFeatureEnabled('invoice_management') ? 'YES' : 'NO') . "\n";
        }
    }
    
    // Step 2: Already converted check
    $alreadyConverted = ($testQuotation->status === 'converted' || \App\Models\Invoice::where('quotation_id', $testQuotation->id)->exists());
    echo "2. Already converted: " . ($alreadyConverted ? 'FAIL' : 'PASS') . "\n";
    
    // Step 3: Subscription check
    $subscriptionCheck = ($subscription && $subscription->isFeatureEnabled('invoice_management'));
    echo "3. Subscription check: " . ($subscriptionCheck ? 'PASS' : 'FAIL') . "\n";
    
    // Step 4: Limits check
    $limitCheck = ($subscription && $subscription->canUseFeature('invoice_management', 1));
    echo "4. Limit check: " . ($limitCheck ? 'PASS' : 'FAIL') . "\n";
    
    echo "\n=== PREDICTION ===\n";
    if ($permissionCheck && !$alreadyConverted && $subscriptionCheck && $limitCheck) {
        echo "✅ Conversion should SUCCEED\n";
    } else {
        echo "❌ Conversion will FAIL\n";
        echo "Failed checks:\n";
        if (!$permissionCheck) echo "  - Permission check\n";
        if ($alreadyConverted) echo "  - Already converted\n";
        if (!$subscriptionCheck) echo "  - Subscription check\n";
        if (!$limitCheck) echo "  - Limit check\n";
    }
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. Try converting a quotation with user: amazonq@asd.cm\n";
echo "2. Check the Laravel log file for detailed error messages\n";
echo "3. Look for log entries starting with 'Conversion Step' or 'Conversion FAILED'\n";