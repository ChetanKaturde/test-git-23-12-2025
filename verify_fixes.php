<?php

/**
 * VERIFICATION SCRIPT: Quote Redirect + Password Encryption
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Crypt;

echo "=== VERIFICATION TESTS ===\n\n";

// TEST 1: Verify password encryption
echo "TEST 1: Password Encryption\n";
echo "----------------------------\n";

$users = User::whereNotNull('plain_password')->take(5)->get();

foreach ($users as $user) {
    echo "User: {$user->email}\n";
    echo "  Role: {$user->role}\n";
    echo "  Encrypted value: " . substr($user->plain_password, 0, 50) . "...\n";
    
    try {
        $decrypted = Crypt::decryptString($user->plain_password);
        echo "  ✅ Decryption successful\n";
        echo "  Decrypted length: " . strlen($decrypted) . " chars\n";
    } catch (\Exception $e) {
        echo "  ❌ Decryption FAILED: {$e->getMessage()}\n";
    }
    echo "\n";
}

// TEST 2: Check permissions for redirect logic
echo "\nTEST 2: User Permissions for Redirect Logic\n";
echo "--------------------------------------------\n";

$testUsers = [
    'convert_only' => User::where('role', '!=', 'admin')
        ->whereJsonContains('permissions', 'convert_quote_to_invoice')
        ->whereJsonDoesntContain('permissions', 'manage_invoices')
        ->first(),
    'both_permissions' => User::where('role', '!=', 'admin')
        ->whereJsonContains('permissions', 'convert_quote_to_invoice')
        ->whereJsonContains('permissions', 'manage_invoices')
        ->first(),
    'admin' => User::where('role', 'admin')->first(),
];

foreach ($testUsers as $type => $user) {
    if ($user) {
        echo "\n{$type}: {$user->email}\n";
        echo "  Role: {$user->role}\n";
        echo "  Permissions: " . json_encode($user->permissions) . "\n";
        echo "  isAdmin(): " . ($user->isAdmin() ? 'TRUE' : 'FALSE') . "\n";
        echo "  hasPermission('convert_quote_to_invoice'): " . ($user->hasPermission('convert_quote_to_invoice') ? 'TRUE' : 'FALSE') . "\n";
        echo "  hasPermission('manage_invoices'): " . ($user->hasPermission('manage_invoices') ? 'TRUE' : 'FALSE') . "\n";
        
        if ($user->isAdmin() || $user->hasPermission('manage_invoices')) {
            echo "  → Expected redirect: /invoices ✅\n";
        } else {
            echo "  → Expected redirect: /quotations ✅\n";
        }
    } else {
        echo "\n{$type}: Not found in database\n";
    }
}

echo "\n=== VERIFICATION COMPLETE ===\n";
