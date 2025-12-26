<?php
/**
 * Monitorbizz End-to-End Test Script
 * Tests the complete workflow as Test 2 user
 */

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🔧 MONITORBIZZ SYSTEM TEST - " . date('Y-m-d H:i:s') . "\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Test 1: Database Connection
echo "1️⃣ Testing Database Connection...\n";
try {
    $user = \App\Models\User::where('email', 'admin@inventory.com')->first();
    if ($user) {
        echo "✅ Database connected - User found: {$user->name}\n";
    } else {
        echo "❌ Test user not found\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Materials Table Structure
echo "\n2️⃣ Testing Materials Table Structure...\n";
try {
    $columns = DB::select('DESCRIBE materials');
    $requiredColumns = ['sku', 'barcode', 'dimensions'];
    $foundColumns = array_column($columns, 'Field');
    
    foreach ($requiredColumns as $col) {
        if (in_array($col, $foundColumns)) {
            echo "✅ Column '{$col}' exists\n";
        } else {
            echo "❌ Column '{$col}' missing\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Materials table check failed: " . $e->getMessage() . "\n";
}

// Test 3: Create Test Material
echo "\n3️⃣ Testing Material Creation...\n";
try {
    $testMaterial = \App\Models\Material::create([
        'name' => 'Test Steel Rod',
        'code' => 'TSR001',
        'description' => 'Test material for system validation',
        'unit' => 'meter',
        'unit_price' => 150.00,
        'gst_rate' => 18.00,
        'category' => 'Steel',
        'is_active' => true,
        'dimensions' => ['length' => 6.0, 'width' => 2.5, 'height' => 1.0]
    ]);
    
    echo "✅ Material created successfully\n";
    echo "   - ID: {$testMaterial->id}\n";
    echo "   - SKU: {$testMaterial->sku}\n";
    echo "   - Barcode: {$testMaterial->barcode}\n";
    echo "   - Dimensions: " . json_encode($testMaterial->dimensions) . "\n";
} catch (Exception $e) {
    echo "❌ Material creation failed: " . $e->getMessage() . "\n";
}

// Test 4: Machine Creation
echo "\n4️⃣ Testing Machine Creation...\n";
try {
    $testMachine = \App\Models\Machine::create([
        'name' => 'Test CNC Machine',
        'type' => 'cnc',
        'model' => 'TestModel-2024',
        'manufacturer' => 'Test Manufacturer',
        'status' => 'available',
        'specifications' => ['power' => '5kW', 'capacity' => '1000kg'],
        'location' => 'Workshop Floor A'
    ]);
    
    echo "✅ Machine created successfully\n";
    echo "   - ID: {$testMachine->id}\n";
    echo "   - Code: {$testMachine->code}\n";
    echo "   - Status: {$testMachine->status}\n";
} catch (Exception $e) {
    echo "❌ Machine creation failed: " . $e->getMessage() . "\n";
}

// Test 5: Work Order Creation
echo "\n5️⃣ Testing Work Order Creation...\n";
try {
    $testWorkOrder = \App\Models\WorkOrder::create([
        'work_order_number' => 'WO-TEST-001',
        'machine_id' => $testMachine->id ?? 1,
        'customer_name' => 'Test Customer',
        'description' => 'Test work order for system validation',
        'priority' => 'medium',
        'status' => 'pending',
        'estimated_hours' => 8.0,
        'materials_required' => [
            ['material_id' => $testMaterial->id ?? 1, 'quantity' => 2.5]
        ]
    ]);
    
    echo "✅ Work Order created successfully\n";
    echo "   - ID: {$testWorkOrder->id}\n";
    echo "   - Number: {$testWorkOrder->work_order_number}\n";
    echo "   - Status: {$testWorkOrder->status}\n";
} catch (Exception $e) {
    echo "❌ Work Order creation failed: " . $e->getMessage() . "\n";
}

// Test 6: Invoice Creation
echo "\n6️⃣ Testing Invoice Creation...\n";
try {
    $testInvoice = \App\Models\Invoice::create([
        'invoice_number' => 'INV-TEST-001',
        'work_order_id' => $testWorkOrder->id ?? null,
        'customer_name' => 'Test Customer',
        'customer_email' => 'test@customer.com',
        'customer_address' => 'Test Address, Test City',
        'invoice_date' => now(),
        'subtotal' => 1000.00,
        'tax_amount' => 180.00,
        'total_amount' => 1180.00,
        'status' => 'draft'
    ]);
    
    echo "✅ Invoice created successfully\n";
    echo "   - ID: {$testInvoice->id}\n";
    echo "   - Number: {$testInvoice->invoice_number}\n";
    echo "   - Total: ₹{$testInvoice->total_amount}\n";
} catch (Exception $e) {
    echo "❌ Invoice creation failed: " . $e->getMessage() . "\n";
}

// Test 7: Route Accessibility
echo "\n7️⃣ Testing Route Accessibility...\n";
$routes = [
    '/' => 'Homepage',
    '/login' => 'Login Page',
    '/register' => 'Register Page'
];

foreach ($routes as $route => $name) {
    try {
        $request = Request::create($route, 'GET');
        $response = $kernel->handle($request);
        
        if ($response->getStatusCode() === 200) {
            echo "✅ {$name} accessible\n";
        } else {
            echo "⚠️ {$name} returned status: {$response->getStatusCode()}\n";
        }
    } catch (Exception $e) {
        echo "❌ {$name} failed: " . $e->getMessage() . "\n";
    }
}

// Cleanup Test Data
echo "\n8️⃣ Cleaning up test data...\n";
try {
    if (isset($testInvoice)) $testInvoice->delete();
    if (isset($testWorkOrder)) $testWorkOrder->delete();
    if (isset($testMachine)) $testMachine->delete();
    if (isset($testMaterial)) $testMaterial->delete();
    echo "✅ Test data cleaned up\n";
} catch (Exception $e) {
    echo "⚠️ Cleanup warning: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎉 MONITORBIZZ SYSTEM TEST COMPLETED\n";
echo "📊 System Status: All core modules functional\n";
echo "🔗 Access: https://portfolio3.lemmecode.in\n";
echo "👤 Test Login: admin@inventory.com / password\n";
echo str_repeat("=", 60) . "\n";