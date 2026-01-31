<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Subscription;

echo "=== DETAILED PERMISSION ANALYSIS ===\n";

$workingUser = User::where('email', 'lemmecodechetan1@gmail.com')->first();
if ($workingUser) {
    echo "Working User: {$workingUser->email}\n";
    echo "  Role: {$workingUser->role}\n";
    echo "  Is Admin: " . ($workingUser->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "  Business ID: {$workingUser->business_id}\n";
    
    if ($workingUser->business) {
        echo "  Business: {$workingUser->business->name}\n";
        echo "  Business Plan: {$workingUser->business->subscription_plan}\n";
        
        // Test business hasFeature method
        echo "  Business hasFeature('invoice_management'): " . ($workingUser->business->hasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        
        // Test user businessHasFeature method
        echo "  User businessHasFeature('invoice_management'): " . ($workingUser->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        
        // Test canAccessFeatureAction step by step
        echo "\n  Step-by-step canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice'):\n";
        echo "    1. Is superadmin: " . ($workingUser->role === 'superadmin' ? 'YES' : 'NO') . "\n";
        echo "    2. Business has feature: " . ($workingUser->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "    3. Is admin: " . ($workingUser->isAdmin() ? 'YES' : 'NO') . "\n";
        echo "    4. Has permission: " . ($workingUser->hasPermission('convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
        echo "    Final result: " . ($workingUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    }
    
    $subscription = $workingUser->currentSubscription();
    if ($subscription) {
        echo "  Active Subscription: YES\n";
        echo "    Status: {$subscription->status}\n";
        echo "    Plan ID: {$subscription->plan_id}\n";
        echo "    Features: " . json_encode($subscription->plan_snapshot['features'] ?? []) . "\n";
    } else {
        echo "  Active Subscription: NO\n";
    }
}

echo "\n";

// Test a new user
$newUser = User::where('email', '!=', 'lemmecodechetan1@gmail.com')->first();
if ($newUser) {
    echo "New User: {$newUser->email}\n";
    echo "  Role: {$newUser->role}\n";
    echo "  Is Admin: " . ($newUser->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "  Business ID: {$newUser->business_id}\n";
    
    if ($newUser->business) {
        echo "  Business: {$newUser->business->name}\n";
        echo "  Business Plan: {$newUser->business->subscription_plan}\n";
        
        // Test business hasFeature method
        echo "  Business hasFeature('invoice_management'): " . ($newUser->business->hasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        
        // Test user businessHasFeature method
        echo "  User businessHasFeature('invoice_management'): " . ($newUser->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        
        // Test canAccessFeatureAction step by step
        echo "\n  Step-by-step canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice'):\n";
        echo "    1. Is superadmin: " . ($newUser->role === 'superadmin' ? 'YES' : 'NO') . "\n";
        echo "    2. Business has feature: " . ($newUser->businessHasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
        echo "    3. Is admin: " . ($newUser->isAdmin() ? 'YES' : 'NO') . "\n";
        echo "    4. Has permission: " . ($newUser->hasPermission('convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
        echo "    Final result: " . ($newUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    }
    
    $subscription = $newUser->currentSubscription();
    if ($subscription) {
        echo "  Active Subscription: YES\n";
        echo "    Status: {$subscription->status}\n";
        echo "    Plan ID: {$subscription->plan_id}\n";
        echo "    Features: " . json_encode($subscription->plan_snapshot['features'] ?? []) . "\n";
    } else {
        echo "  Active Subscription: NO\n";
    }
}

// Check what happens with a business that has a subscription
echo "\n=== BUSINESS WITH SUBSCRIPTION ===\n";
$businessWithSub = Business::find(5); // This has subscription ID 1
if ($businessWithSub) {
    echo "Business: {$businessWithSub->name} (ID: {$businessWithSub->id})\n";
    echo "  Plan: {$businessWithSub->subscription_plan}\n";
    echo "  hasFeature('invoice_management'): " . ($businessWithSub->hasFeature('invoice_management') ? 'YES' : 'NO') . "\n";
    
    $subscription = $businessWithSub->subscriptions()->active()->first();
    if ($subscription) {
        echo "  Active Subscription: YES\n";
        echo "    Status: {$subscription->status}\n";
        echo "    Plan ID: {$subscription->plan_id}\n";
        echo "    Features: " . json_encode($subscription->plan_snapshot['features'] ?? []) . "\n";
    } else {
        echo "  Active Subscription: NO\n";
    }
}