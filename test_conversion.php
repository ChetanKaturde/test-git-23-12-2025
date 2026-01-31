<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Quotation;

echo "=== QUOTATION CONVERSION TEST ===\n";

// Test with working user
$workingUser = User::where('email', 'lemmecodechetan1@gmail.com')->first();
if ($workingUser) {
    echo "Working User: {$workingUser->email}\n";
    
    // Get a quotation from this user's business
    $quotation = Quotation::where('business_id', $workingUser->business_id)
                          ->where('status', '!=', 'converted')
                          ->first();
    
    if ($quotation) {
        echo "  Found quotation: {$quotation->number} (Status: {$quotation->status})\n";
        
        // Test the conversion logic step by step
        echo "  Testing conversion logic:\n";
        
        // Check permission
        $hasPermission = $workingUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice');
        echo "    1. Has permission: " . ($hasPermission ? 'YES' : 'NO') . "\n";
        
        // Check subscription
        $subscription = $workingUser->currentSubscription();
        echo "    2. Has subscription: " . ($subscription ? 'YES' : 'NO') . "\n";
        
        if ($subscription) {
            $featureEnabled = $subscription->isFeatureEnabled('invoice_management');
            echo "    3. Feature enabled: " . ($featureEnabled ? 'YES' : 'NO') . "\n";
            
            $canUseFeature = $subscription->canUseFeature('invoice_management', 1);
            echo "    4. Can use feature: " . ($canUseFeature ? 'YES' : 'NO') . "\n";
        } else {
            echo "    3. Feature enabled: N/A (no subscription)\n";
            echo "    4. Can use feature: N/A (no subscription)\n";
        }
        
        // Check if already converted
        $alreadyConverted = $quotation->status === 'converted' || \App\Models\Invoice::where('quotation_id', $quotation->id)->exists();
        echo "    5. Already converted: " . ($alreadyConverted ? 'YES' : 'NO') . "\n";
        
    } else {
        echo "  No quotations found for this business\n";
    }
}

echo "\n";

// Test with a user that has subscription
$userWithSub = User::whereHas('business.subscriptions', function($q) {
    $q->where('status', 'active');
})->first();

if ($userWithSub) {
    echo "User with Subscription: {$userWithSub->email}\n";
    echo "  Business: {$userWithSub->business->name} (ID: {$userWithSub->business_id})\n";
    
    // Test the conversion logic
    echo "  Testing conversion logic:\n";
    
    // Check permission
    $hasPermission = $userWithSub->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice');
    echo "    1. Has permission: " . ($hasPermission ? 'YES' : 'NO') . "\n";
    
    // Check subscription
    $subscription = $userWithSub->currentSubscription();
    echo "    2. Has subscription: " . ($subscription ? 'YES' : 'NO') . "\n";
    
    if ($subscription) {
        $featureEnabled = $subscription->isFeatureEnabled('invoice_management');
        echo "    3. Feature enabled: " . ($featureEnabled ? 'YES' : 'NO') . "\n";
        
        $canUseFeature = $subscription->canUseFeature('invoice_management', 1);
        echo "    4. Can use feature: " . ($canUseFeature ? 'YES' : 'NO') . "\n";
    }
}

// Check the view logic
echo "\n=== VIEW LOGIC TEST ===\n";
echo "Testing the quotation show view logic:\n";

// Simulate what happens in the view
$user = $workingUser;
if ($user && $user->business) {
    $canCreateInvoice = $user->business->canCreateInvoice();
    echo "  canCreateInvoice (from Business model): " . ($canCreateInvoice ? 'YES' : 'NO') . "\n";
    
    $isAdmin = $user->isAdmin();
    echo "  isAdmin: " . ($isAdmin ? 'YES' : 'NO') . "\n";
    
    $hasPermission = $user->hasPermission('convert_quotation_to_invoice');
    echo "  hasPermission('convert_quotation_to_invoice'): " . ($hasPermission ? 'YES' : 'NO') . "\n";
    
    // This is the condition from the view:
    // @if(auth()->user()->isAdmin() || ($canCreateInvoice && auth()->user()->hasPermission('convert_quotation_to_invoice')))
    $showButton = $isAdmin || ($canCreateInvoice && $hasPermission);
    echo "  Show convert button: " . ($showButton ? 'YES' : 'NO') . "\n";
}