<?php

/**
 * TEST SCRIPT: Reports Permission Check
 * 
 * This script tests the reports permission logic
 * Run: php test_reports_permission.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Log;

echo "=== REPORTS PERMISSION TEST ===\n\n";

// Test 1: Find an admin user
echo "TEST 1: Admin User\n";
$admin = User::where('role', 'admin')->first();
if ($admin) {
    echo "  User: {$admin->email}\n";
    echo "  Role: {$admin->role}\n";
    echo "  isAdmin(): " . ($admin->isAdmin() ? 'TRUE' : 'FALSE') . "\n";
    echo "  Permissions JSON: " . json_encode($admin->permissions) . "\n";
    echo "  hasPermission('view_reports'): " . ($admin->hasPermission('view_reports') ? 'TRUE' : 'FALSE') . "\n";
    echo "  EXPECTED: Admin should ALWAYS have access (bypass permission check)\n";
} else {
    echo "  No admin user found\n";
}

echo "\n";

// Test 2: Find a team member
echo "TEST 2: Team Member WITHOUT view_reports\n";
$teamMemberWithout = User::where('role', '!=', 'admin')
    ->whereJsonDoesntContain('permissions', 'view_reports')
    ->first();
    
if ($teamMemberWithout) {
    echo "  User: {$teamMemberWithout->email}\n";
    echo "  Role: {$teamMemberWithout->role}\n";
    echo "  isAdmin(): " . ($teamMemberWithout->isAdmin() ? 'TRUE' : 'FALSE') . "\n";
    echo "  Permissions JSON: " . json_encode($teamMemberWithout->permissions) . "\n";
    echo "  hasPermission('view_reports'): " . ($teamMemberWithout->hasPermission('view_reports') ? 'TRUE' : 'FALSE') . "\n";
    echo "  EXPECTED: Should be DENIED access (403)\n";
} else {
    echo "  No team member without view_reports found\n";
}

echo "\n";

// Test 3: Find a team member WITH view_reports
echo "TEST 3: Team Member WITH view_reports\n";
$teamMemberWith = User::where('role', '!=', 'admin')
    ->whereJsonContains('permissions', 'view_reports')
    ->first();
    
if ($teamMemberWith) {
    echo "  User: {$teamMemberWith->email}\n";
    echo "  Role: {$teamMemberWith->role}\n";
    echo "  isAdmin(): " . ($teamMemberWith->isAdmin() ? 'TRUE' : 'FALSE') . "\n";
    echo "  Permissions JSON: " . json_encode($teamMemberWith->permissions) . "\n";
    echo "  hasPermission('view_reports'): " . ($teamMemberWith->hasPermission('view_reports') ? 'TRUE' : 'FALSE') . "\n";
    echo "  EXPECTED: Should be ALLOWED access\n";
} else {
    echo "  No team member with view_reports found\n";
}

echo "\n";

// Test 4: Check all users and their permissions
echo "TEST 4: All Users Summary\n";
$allUsers = User::all();
foreach ($allUsers as $user) {
    $hasViewReports = is_array($user->permissions) && in_array('view_reports', $user->permissions);
    echo "  {$user->email} | Role: {$user->role} | view_reports: " . ($hasViewReports ? 'YES' : 'NO') . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
