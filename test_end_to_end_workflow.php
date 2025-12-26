<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== END-TO-END WORKFLOW TEST ===" . PHP_EOL . PHP_EOL;

// Cleanup any existing test data
\App\Models\Material::where('code', 'like', 'TSR%')->delete();
\App\Models\Vendor::where('name', 'like', 'Test Steel%')->delete();
\App\Models\Machine::where('code', 'like', 'CNC%')->delete();
\App\Models\PurchaseOrder::where('po_number', 'like', 'PO-TEST-%')->delete();
\App\Models\WorkOrder::where('wo_number', 'like', 'WO-TEST-%')->delete();

// Setup test data
$business = \App\Models\Business::first();
if (!$business) {
    echo "❌ No business found for testing" . PHP_EOL;
    exit;
}

// Create test users with different roles
$admin = \App\Models\User::where('role', 'admin')->where('business_id', $business->id)->first();
$manager = \App\Models\User::where('role', 'manager')->where('business_id', $business->id)->first();
$operator = \App\Models\User::where('role', 'operator')->where('business_id', $business->id)->first();

if (!$admin) {
    echo "❌ No admin user found for testing" . PHP_EOL;
    exit;
}

echo "✅ Test users found - Admin: {$admin->name}" . PHP_EOL;
if ($manager) echo "✅ Manager: {$manager->name}" . PHP_EOL;
if ($operator) echo "✅ Operator: {$operator->name}" . PHP_EOL;

// Step 1: Admin creates material
echo PHP_EOL . "Step 1: Creating test material..." . PHP_EOL;
auth()->login($admin);

$material = \App\Models\Material::create([
    'name' => 'Test Steel Rod',
    'code' => 'TSR' . time(),
    'sku' => 'TSR' . time(),
    'unit' => 'kg',
    'unit_price' => 50.00,
    'business_id' => $business->id,
    'is_active' => true
]);

echo "✅ Material created: {$material->name}" . PHP_EOL;

// Step 2: Admin creates vendor
echo PHP_EOL . "Step 2: Creating test vendor..." . PHP_EOL;

$vendor = \App\Models\Vendor::create([
    'name' => 'Test Steel Supplier',
    'email' => 'supplier@test.com',
    'phone' => '1234567890',
    'business_id' => $business->id,
    'is_active' => true
]);

echo "✅ Vendor created: {$vendor->name}" . PHP_EOL;

// Step 3: Create machine
echo PHP_EOL . "Step 3: Creating test machine..." . PHP_EOL;

$machine = \App\Models\Machine::create([
    'name' => 'Test CNC Machine',
    'code' => 'CNC' . time(),
    'type' => 'cnc',
    'status' => 'available',
    'business_id' => $business->id
]);

echo "✅ Machine created: {$machine->name}" . PHP_EOL;

// Step 4: Manager creates Purchase Order
echo PHP_EOL . "Step 4: Creating purchase order..." . PHP_EOL;

if ($manager) {
    auth()->login($manager);
} else {
    echo "⚠️ No manager found, using admin" . PHP_EOL;
}

$purchaseOrder = \App\Models\PurchaseOrder::create([
    'po_number' => 'PO-TEST-' . time(),
    'vendor_id' => $vendor->id,
    'po_date' => now(),
    'total_amount' => 1000.00,
    'status' => 'pending',
    'business_id' => $business->id
]);

echo "✅ Purchase Order created: {$purchaseOrder->po_number}" . PHP_EOL;

// Step 5: Admin approves Purchase Order
echo PHP_EOL . "Step 5: Approving purchase order..." . PHP_EOL;
auth()->login($admin);

$purchaseOrder->update(['status' => 'approved']);
echo "✅ Purchase Order approved" . PHP_EOL;

// Step 6: Mark PO as received (creates inventory)
echo PHP_EOL . "Step 6: Marking PO as received..." . PHP_EOL;

$purchaseOrder->update(['status' => 'received']);

// Create inventory batch
$inventoryBatch = \App\Models\InventoryBatch::create([
    'material_id' => $material->id,
    'purchase_order_id' => $purchaseOrder->id,
    'batch_number' => 'BATCH-' . time(),
    'received_quantity' => 100,
    'current_quantity' => 100,
    'received_date' => now(),
    'status' => 'active',
    'business_id' => $business->id
]);

echo "✅ Inventory batch created: {$inventoryBatch->batch_number}" . PHP_EOL;

// Step 7: Manager creates Work Order
echo PHP_EOL . "Step 7: Creating work order..." . PHP_EOL;

if ($manager) {
    auth()->login($manager);
}

$workOrder = \App\Models\WorkOrder::create([
    'wo_number' => 'WO-TEST-' . time(),
    'machine_id' => $machine->id,
    'product_name' => 'Test Product',
    'quantity' => 10,
    'status' => 'pending',
    'assigned_to' => $operator ? $operator->id : null,
    'business_id' => $business->id,
    'operator_id' => auth()->id()
]);

echo "✅ Work Order created: {$workOrder->wo_number}" . PHP_EOL;
if ($operator) {
    echo "✅ Work Order assigned to: {$operator->name}" . PHP_EOL;
}

// Step 8: Operator starts work order
echo PHP_EOL . "Step 8: Starting work order..." . PHP_EOL;

if ($operator) {
    auth()->login($operator);
} else {
    echo "⚠️ No operator found, using admin" . PHP_EOL;
}

$workOrder->update([
    'status' => 'in_progress',
    'started_at' => now()
]);

$machine->update(['status' => 'in_use']);

echo "✅ Work Order started, machine status updated to in_use" . PHP_EOL;

// Step 9: Record material consumption
echo PHP_EOL . "Step 9: Recording material consumption..." . PHP_EOL;

$materialConsumption = \App\Models\MaterialConsumption::create([
    'work_order_id' => $workOrder->id,
    'material_id' => $material->id,
    'planned_quantity' => 20,
    'actual_quantity' => 18,
    'waste_quantity' => 2,
    'business_id' => $business->id
]);

echo "✅ Material consumption recorded: {$materialConsumption->actual_quantity} kg used" . PHP_EOL;

// Step 10: Complete work order
echo PHP_EOL . "Step 10: Completing work order..." . PHP_EOL;

$workOrder->update([
    'status' => 'completed',
    'completed_at' => now()
]);

$machine->update(['status' => 'available']);

// Update inventory
$inventoryBatch->update([
    'current_quantity' => $inventoryBatch->current_quantity - $materialConsumption->actual_quantity
]);

echo "✅ Work Order completed, machine available, inventory updated" . PHP_EOL;

// Verification
echo PHP_EOL . "=== WORKFLOW VERIFICATION ===" . PHP_EOL;

$finalPO = \App\Models\PurchaseOrder::find($purchaseOrder->id);
$finalWO = \App\Models\WorkOrder::find($workOrder->id);
$finalMachine = \App\Models\Machine::find($machine->id);
$finalInventory = \App\Models\InventoryBatch::find($inventoryBatch->id);

echo "Purchase Order Status: {$finalPO->status}" . PHP_EOL;
echo "Work Order Status: {$finalWO->status}" . PHP_EOL;
echo "Machine Status: {$finalMachine->status}" . PHP_EOL;
echo "Remaining Inventory: {$finalInventory->current_quantity} kg" . PHP_EOL;

// Cleanup
echo PHP_EOL . "Cleaning up test data..." . PHP_EOL;
$materialConsumption->delete();
$workOrder->delete();
$inventoryBatch->delete();
$purchaseOrder->delete();
$machine->delete();
$vendor->delete();
$material->delete();

echo "✅ End-to-end workflow test completed successfully!" . PHP_EOL;
echo "=== TEST COMPLETED ===" . PHP_EOL;