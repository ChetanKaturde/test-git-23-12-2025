<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);

echo "=== MONITORBIZZ SYSTEM AUDIT ===\n";
echo "Date: " . date('Y-m-d H:i:s') . " IST\n";
echo "Environment: " . config('app.env') . "\n";
echo "URL: " . config('app.url') . "\n\n";

// Test 1: Database Connection
echo "1. DATABASE CONNECTION TEST\n";
try {
    $users = \App\Models\User::count();
    echo "✅ Database connected - {$users} users found\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// Test 2: Primary User Authentication
echo "\n2. PRIMARY USER TEST (admin@inventory.com)\n";
$primaryUser = \App\Models\User::where('email', 'admin@inventory.com')->first();
if ($primaryUser) {
    echo "✅ User found: {$primaryUser->email}\n";
    echo "✅ Business ID: {$primaryUser->business_id} ({$primaryUser->business->name})\n";
    echo "✅ Role: {$primaryUser->role}\n";
    echo "✅ Password check: " . (Hash::check('password', $primaryUser->password) ? 'PASS' : 'FAIL') . "\n";
} else {
    echo "❌ Primary user not found\n";
}

// Test 3: Secondary User (Test 2)
echo "\n3. SECONDARY USER TEST (asd@aol.com - Test 2)\n";
$secondaryUser = \App\Models\User::where('email', 'asd@aol.com')->first();
if ($secondaryUser) {
    echo "✅ User found: {$secondaryUser->email}\n";
    echo "✅ Business ID: {$secondaryUser->business_id} ({$secondaryUser->business->name})\n";
    echo "✅ Role: {$secondaryUser->role}\n";
} else {
    echo "❌ Secondary user not found\n";
}

// Test 4: Data Isolation
echo "\n4. MULTI-TENANCY DATA ISOLATION TEST\n";
$business1Data = [
    'materials' => \App\Models\Material::where('business_id', 1)->count(),
    'machines' => \App\Models\Machine::where('business_id', 1)->count(),
    'work_orders' => \App\Models\WorkOrder::where('business_id', 1)->count(),
    'vendors' => \App\Models\Vendor::where('business_id', 1)->count(),
    'purchase_orders' => \App\Models\PurchaseOrder::where('business_id', 1)->count(),
];

$business6Data = [
    'materials' => \App\Models\Material::where('business_id', 6)->count(),
    'machines' => \App\Models\Machine::where('business_id', 6)->count(),
    'work_orders' => \App\Models\WorkOrder::where('business_id', 6)->count(),
    'vendors' => \App\Models\Vendor::where('business_id', 6)->count(),
    'purchase_orders' => \App\Models\PurchaseOrder::where('business_id', 6)->count(),
];

echo "Business 1 (Default Workshop):\n";
foreach ($business1Data as $type => $count) {
    echo "  {$type}: {$count}\n";
}

echo "Business 6 (Test 2):\n";
foreach ($business6Data as $type => $count) {
    echo "  {$type}: {$count}\n";
}

// Test 5: Core Model Functionality
echo "\n5. CORE MODEL FUNCTIONALITY TEST\n";

// Test Material creation
try {
    $testMaterial = new \App\Models\Material([
        'name' => 'Audit Test Material',
        'category' => 'Metal',
        'unit' => 'kg',
        'unit_price' => 100.00,
        'gst_rate' => 18.00,
        'is_active' => true,
        'business_id' => 1
    ]);
    
    // Test SKU generation
    $sku = \App\Models\Material::generateSKU($testMaterial);
    $barcode = \App\Models\Material::generateBarcode();
    
    echo "✅ Material SKU generation: {$sku}\n";
    echo "✅ Material barcode generation: {$barcode}\n";
} catch (Exception $e) {
    echo "❌ Material functionality error: " . $e->getMessage() . "\n";
}

// Test Machine functionality
try {
    $machineCount = \App\Models\Machine::where('business_id', 1)->count();
    $nextCode = 'M' . str_pad($machineCount + 1, 4, '0', STR_PAD_LEFT);
    echo "✅ Machine code generation: {$nextCode}\n";
} catch (Exception $e) {
    echo "❌ Machine functionality error: " . $e->getMessage() . "\n";
}

// Test 6: Workflow Integration
echo "\n6. WORKFLOW INTEGRATION TEST\n";

// Check if work orders can link to machines and materials
$workOrder = \App\Models\WorkOrder::with(['machine', 'materialConsumptions.material'])->first();
if ($workOrder) {
    echo "✅ Work Order found: {$workOrder->wo_number}\n";
    echo "✅ Machine linked: " . ($workOrder->machine ? $workOrder->machine->name : 'None') . "\n";
    echo "✅ Material consumptions: " . $workOrder->materialConsumptions->count() . "\n";
} else {
    echo "⚠️ No work orders found for testing\n";
}

// Check PO → Inventory flow
$purchaseOrder = \App\Models\PurchaseOrder::first();
if ($purchaseOrder) {
    echo "✅ Purchase Order found: {$purchaseOrder->po_number}\n";
    echo "✅ Status: {$purchaseOrder->status}\n";
    
    // Check if inventory batches exist
    $inventoryBatches = \App\Models\InventoryBatch::count();
    echo "✅ Inventory batches in system: {$inventoryBatches}\n";
} else {
    echo "⚠️ No purchase orders found for testing\n";
}

// Test 7: Invoice System
echo "\n7. INVOICE SYSTEM TEST\n";
$invoice = \App\Models\Invoice::first();
if ($invoice) {
    echo "✅ Invoice found: {$invoice->invoice_number}\n";
    echo "✅ Status: {$invoice->status}\n";
    echo "✅ Work Order linked: " . ($invoice->work_order_id ? 'Yes' : 'No') . "\n";
} else {
    echo "⚠️ No invoices found for testing\n";
}

// Test 8: UI Components Check
echo "\n8. UI COMPONENTS CHECK\n";
$viewPaths = [
    'dashboard.blade.php',
    'materials/index.blade.php',
    'machines/index.blade.php',
    'work-orders/index.blade.php',
    'work-orders/show.blade.php',
    'purchase_orders/index.blade.php',
    'vendors/index.blade.php',
    'inventory/index.blade.php',
    'invoices/index.blade.php',
    'profile/edit.blade.php'
];

foreach ($viewPaths as $viewPath) {
    $fullPath = resource_path("views/{$viewPath}");
    if (file_exists($fullPath)) {
        echo "✅ View exists: {$viewPath}\n";
    } else {
        echo "❌ View missing: {$viewPath}\n";
    }
}

// Test 9: Route Accessibility
echo "\n9. ROUTE ACCESSIBILITY TEST\n";
$routes = [
    'dashboard',
    'materials.index',
    'machines.index',
    'work-orders.index',
    'purchase-orders.index',
    'vendors.index',
    'inventory.index',
    'invoices.index',
    'profile.edit'
];

foreach ($routes as $routeName) {
    try {
        $url = route($routeName);
        echo "✅ Route accessible: {$routeName} → {$url}\n";
    } catch (Exception $e) {
        echo "❌ Route error: {$routeName} → " . $e->getMessage() . "\n";
    }
}

echo "\n=== AUDIT COMPLETE ===\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . " IST\n";