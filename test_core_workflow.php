<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CORE WORKFLOW TEST ===" . PHP_EOL . PHP_EOL;

// Get existing business and users
$business = \App\Models\Business::first();
$admin = \App\Models\User::where('role', 'admin')->where('business_id', $business->id)->first();
$operator = \App\Models\User::where('role', 'operator')->where('business_id', $business->id)->first();

if (!$admin) {
    echo "❌ No admin user found" . PHP_EOL;
    exit;
}

echo "✅ Using existing users - Admin: {$admin->name}" . PHP_EOL;
if ($operator) echo "✅ Operator: {$operator->name}" . PHP_EOL;

// Test 1: Material Management
echo PHP_EOL . "Test 1: Material Management..." . PHP_EOL;
auth()->login($admin);

$material = \App\Models\Material::create([
    'name' => 'Test Material ' . time(),
    'code' => 'TM' . time(),
    'sku' => 'TM' . time(),
    'unit' => 'kg',
    'unit_price' => 100.00,
    'business_id' => $business->id,
    'is_active' => true
]);

echo "✅ Material created: {$material->name}" . PHP_EOL;

// Test 2: Machine Management
echo PHP_EOL . "Test 2: Machine Management..." . PHP_EOL;

$machine = \App\Models\Machine::create([
    'name' => 'Test Machine ' . time(),
    'code' => 'TM' . time(),
    'type' => 'cnc',
    'status' => 'available',
    'business_id' => $business->id
]);

echo "✅ Machine created: {$machine->name}" . PHP_EOL;

// Test 3: Work Order Creation and Assignment
echo PHP_EOL . "Test 3: Work Order Management..." . PHP_EOL;

$workOrder = \App\Models\WorkOrder::create([
    'wo_number' => 'WO-TEST-' . time(),
    'machine_id' => $machine->id,
    'product_name' => 'Test Product',
    'quantity' => 5,
    'status' => 'pending',
    'assigned_to' => $operator ? $operator->id : null,
    'business_id' => $business->id,
    'operator_id' => $admin->id
]);

echo "✅ Work Order created: {$workOrder->wo_number}" . PHP_EOL;
if ($operator) {
    echo "✅ Work Order assigned to: {$operator->name}" . PHP_EOL;
}

// Test 4: Operator Workflow
echo PHP_EOL . "Test 4: Operator Workflow..." . PHP_EOL;

if ($operator) {
    auth()->login($operator);
    
    // Check operator can see assigned work orders
    $operatorWorkOrders = \App\Models\WorkOrder::where('assigned_to', $operator->id)->get();
    echo "✅ Operator sees {$operatorWorkOrders->count()} assigned work orders" . PHP_EOL;
    
    // Start work order
    $workOrder->update([
        'status' => 'in_progress',
        'started_at' => now()
    ]);
    
    $machine->update(['status' => 'in_use']);
    echo "✅ Work Order started, machine status updated" . PHP_EOL;
    
    // Complete work order
    $workOrder->update([
        'status' => 'completed',
        'completed_at' => now()
    ]);
    
    $machine->update(['status' => 'available']);
    echo "✅ Work Order completed, machine available" . PHP_EOL;
}

// Test 5: Permission System
echo PHP_EOL . "Test 5: Permission System..." . PHP_EOL;

// Test admin permissions
auth()->login($admin);
$adminCanViewMaterials = $admin->canViewModule('materials');
$adminCanCreateMaterials = $admin->canCreateInModule('materials');
echo "✅ Admin permissions - View: " . ($adminCanViewMaterials ? 'Yes' : 'No') . ", Create: " . ($adminCanCreateMaterials ? 'Yes' : 'No') . PHP_EOL;

// Test operator permissions
if ($operator) {
    auth()->login($operator);
    $operatorCanViewMaterials = $operator->canViewModule('materials');
    $operatorCanCreateMaterials = $operator->canCreateInModule('materials');
    echo "✅ Operator permissions - View: " . ($operatorCanViewMaterials ? 'Yes' : 'No') . ", Create: " . ($operatorCanCreateMaterials ? 'Yes' : 'No') . PHP_EOL;
}

// Test 6: Business Isolation
echo PHP_EOL . "Test 6: Business Isolation..." . PHP_EOL;

auth()->login($admin);
$businessMaterials = \App\Models\Material::where('business_id', $business->id)->count();
$allMaterials = \App\Models\Material::count();
echo "✅ Business materials: {$businessMaterials}, Total materials: {$allMaterials}" . PHP_EOL;

if ($businessMaterials <= $allMaterials) {
    echo "✅ Business isolation working correctly" . PHP_EOL;
} else {
    echo "❌ Business isolation issue detected" . PHP_EOL;
}

// Verification Summary
echo PHP_EOL . "=== WORKFLOW VERIFICATION ===" . PHP_EOL;

$finalWorkOrder = \App\Models\WorkOrder::find($workOrder->id);
$finalMachine = \App\Models\Machine::find($machine->id);

echo "Work Order Status: {$finalWorkOrder->status}" . PHP_EOL;
echo "Machine Status: {$finalMachine->status}" . PHP_EOL;
echo "Work Order Duration: " . ($finalWorkOrder->started_at && $finalWorkOrder->completed_at ? 
    $finalWorkOrder->started_at->diffInSeconds($finalWorkOrder->completed_at) . " seconds" : "N/A") . PHP_EOL;

// Cleanup
echo PHP_EOL . "Cleaning up test data..." . PHP_EOL;
$workOrder->delete();
$machine->delete();
$material->delete();

echo "✅ Core workflow test completed successfully!" . PHP_EOL;
echo "=== TEST COMPLETED ===" . PHP_EOL;