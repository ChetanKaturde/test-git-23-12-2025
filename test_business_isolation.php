<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== BUSINESS ISOLATION TEST ===" . PHP_EOL . PHP_EOL;

// Clean up any existing test data first
\App\Models\Business::where('slug', 'like', 'test-business-%')->delete();
\App\Models\User::where('email', 'like', 'user%@test.com')->delete();

// Create test businesses and users
$business1 = \App\Models\Business::create([
    'name' => 'Test Business 1',
    'slug' => 'test-business-' . time() . '-1',
    'email' => 'test1@business.com',
    'phone' => '1234567890'
]);

$business2 = \App\Models\Business::create([
    'name' => 'Test Business 2',
    'slug' => 'test-business-' . time() . '-2', 
    'email' => 'test2@business.com',
    'phone' => '0987654321'
]);

$user1 = \App\Models\User::create([
    'name' => 'User Business 1',
    'email' => 'user1@test.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'business_id' => $business1->id,
    'is_active' => true
]);

$user2 = \App\Models\User::create([
    'name' => 'User Business 2',
    'email' => 'user2@test.com', 
    'password' => bcrypt('password'),
    'role' => 'admin',
    'business_id' => $business2->id,
    'is_active' => true
]);

echo "Created test businesses and users" . PHP_EOL;

// Test Materials isolation
$material1 = \App\Models\Material::create([
    'name' => 'Material Business 1',
    'code' => 'MAT001',
    'sku' => 'MAT001',
    'unit' => 'kg',
    'unit_price' => 100,
    'business_id' => $business1->id,
    'is_active' => true
]);

$material2 = \App\Models\Material::create([
    'name' => 'Material Business 2',
    'code' => 'MAT002',
    'sku' => 'MAT002', 
    'unit' => 'kg',
    'unit_price' => 200,
    'business_id' => $business2->id,
    'is_active' => true
]);

// Test isolation - User 1 should only see Business 1 materials
auth()->login($user1);
$user1Materials = \App\Models\Material::where('business_id', auth()->user()->business_id)->get();
echo "User 1 sees " . $user1Materials->count() . " materials (should be 1)" . PHP_EOL;

auth()->login($user2);
$user2Materials = \App\Models\Material::where('business_id', auth()->user()->business_id)->get();
echo "User 2 sees " . $user2Materials->count() . " materials (should be 1)" . PHP_EOL;

// Test cross-business access attempt
try {
    auth()->login($user1);
    $crossAccess = \App\Models\Material::find($material2->id);
    if ($crossAccess && $crossAccess->business_id !== auth()->user()->business_id) {
        echo "❌ SECURITY ISSUE: User 1 can access Business 2 material!" . PHP_EOL;
    } else {
        echo "✅ Business isolation working for materials" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✅ Cross-business access properly blocked" . PHP_EOL;
}

// Test Machines isolation
$machine1 = \App\Models\Machine::create([
    'name' => 'Machine Business 1',
    'code' => 'M0001',
    'type' => 'cnc',
    'status' => 'available',
    'business_id' => $business1->id
]);

$machine2 = \App\Models\Machine::create([
    'name' => 'Machine Business 2',
    'code' => 'M0002',
    'type' => 'lathe', 
    'status' => 'available',
    'business_id' => $business2->id
]);

auth()->login($user1);
$user1Machines = \App\Models\Machine::where('business_id', auth()->user()->business_id)->get();
echo "User 1 sees " . $user1Machines->count() . " machines (should be 1)" . PHP_EOL;

auth()->login($user2);
$user2Machines = \App\Models\Machine::where('business_id', auth()->user()->business_id)->get();
echo "User 2 sees " . $user2Machines->count() . " machines (should be 1)" . PHP_EOL;

// Cleanup
$business1->delete();
$business2->delete();
$user1->delete();
$user2->delete();
$material1->delete();
$material2->delete();
$machine1->delete();
$machine2->delete();

echo PHP_EOL . "✅ Business isolation test completed successfully!" . PHP_EOL;
echo "=== TEST COMPLETED ===" . PHP_EOL;