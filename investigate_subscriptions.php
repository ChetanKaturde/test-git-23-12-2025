<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Business;
use App\Models\Subscription;

echo "=== SUBSCRIPTION INVESTIGATION ===\n";

// Check if subscriptions table exists and has data
try {
    $subscriptionCount = DB::table('subscriptions')->count();
    echo "Subscriptions table exists with {$subscriptionCount} records\n";
    
    if ($subscriptionCount > 0) {
        $subs = DB::table('subscriptions')->get();
        foreach ($subs as $sub) {
            echo "  Sub ID: {$sub->id} | Business: {$sub->business_id} | Status: {$sub->status}\n";
        }
    }
} catch (Exception $e) {
    echo "Subscriptions table issue: " . $e->getMessage() . "\n";
}

// Check businesses and their subscription plans
echo "\nBusiness subscription plans:\n";
$businesses = DB::table('businesses')->select('id', 'name', 'subscription_plan', 'subscription_tier')->get();
foreach ($businesses as $biz) {
    echo "  Business {$biz->id}: {$biz->name} | Plan: {$biz->subscription_plan} | Tier: {$biz->subscription_tier}\n";
}

// Check specific user
echo "\nChecking working user:\n";
$user = DB::table('users')->where('email', 'lemmecodechetan1@gmail.com')->first();
if ($user) {
    echo "  User: {$user->email} | Business: {$user->business_id} | Role: {$user->role}\n";
    $business = DB::table('businesses')->where('id', $user->business_id)->first();
    if ($business) {
        echo "  Business Plan: {$business->subscription_plan}\n";
    }
}

// Check a few other users
echo "\nChecking other users:\n";
$otherUsers = DB::table('users')->where('email', '!=', 'lemmecodechetan1@gmail.com')->limit(3)->get();
foreach ($otherUsers as $user) {
    echo "  User: {$user->email} | Business: {$user->business_id} | Role: {$user->role}\n";
    $business = DB::table('businesses')->where('id', $user->business_id)->first();
    if ($business) {
        echo "    Business Plan: {$business->subscription_plan}\n";
    }
}

// Test the specific methods
echo "\n=== TESTING USER METHODS ===\n";
$workingUser = User::where('email', 'lemmecodechetan1@gmail.com')->first();
if ($workingUser) {
    echo "Working User: {$workingUser->email}\n";
    echo "  Role: {$workingUser->role}\n";
    echo "  Business ID: {$workingUser->business_id}\n";
    
    if ($workingUser->business) {
        echo "  Business: {$workingUser->business->name}\n";
        echo "  Business Plan: {$workingUser->business->subscription_plan}\n";
        echo "  Business canCreateInvoice(): " . ($workingUser->business->canCreateInvoice() ? 'YES' : 'NO') . "\n";
    }
    
    $subscription = $workingUser->currentSubscription();
    if ($subscription) {
        echo "  Has Active Subscription: YES\n";
        echo "  Subscription Status: {$subscription->status}\n";
        echo "  Can access invoice_management: " . ($workingUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    } else {
        echo "  Has Active Subscription: NO\n";
        echo "  Can access invoice_management: " . ($workingUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    }
}

echo "\n";

// Check a sample new user
$newUser = User::where('email', '!=', 'lemmecodechetan1@gmail.com')->first();
if ($newUser) {
    echo "New User: {$newUser->email}\n";
    echo "  Role: {$newUser->role}\n";
    echo "  Business ID: {$newUser->business_id}\n";
    
    if ($newUser->business) {
        echo "  Business: {$newUser->business->name}\n";
        echo "  Business Plan: {$newUser->business->subscription_plan}\n";
        echo "  Business canCreateInvoice(): " . ($newUser->business->canCreateInvoice() ? 'YES' : 'NO') . "\n";
    }
    
    $subscription = $newUser->currentSubscription();
    if ($subscription) {
        echo "  Has Active Subscription: YES\n";
        echo "  Subscription Status: {$subscription->status}\n";
        echo "  Can access invoice_management: " . ($newUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    } else {
        echo "  Has Active Subscription: NO\n";
        echo "  Can access invoice_management: " . ($newUser->canAccessFeatureAction('invoice_management', 'convert_quotation_to_invoice') ? 'YES' : 'NO') . "\n";
    }
}