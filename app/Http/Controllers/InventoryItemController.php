<?php

namespace App\Http\Controllers;

use App\Models\InventoryBatch;
use App\Models\InventoryTransaction;
use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryItemController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        $totalBatches = InventoryBatch::count();
        $activeBatches = InventoryBatch::where('status', 'active')->count();
        $expiredBatches = InventoryBatch::where('status', 'expired')->count();
        $damagedBatches = InventoryBatch::where('status', 'damaged')->count();
        $exhaustedBatches = InventoryBatch::where('status', 'exhausted')->count();
        
        $lowStockItems = Material::whereHas('inventoryBatches', function($q) {
            $q->where('current_quantity', '<', 10)
              ->where('status', 'active');
        })->count();

        $totalValue = InventoryBatch::where('status', 'active')
            ->sum(DB::raw('current_quantity * unit_price'));

        $recentTransactions = InventoryTransaction::with(['batch.material', 'batch.purchaseOrder'])
            ->latest('transaction_date')
            ->take(10)
            ->get();

        $expiringBatches = InventoryBatch::where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->count();

        return view('inventory.dashboard', compact(
            'totalBatches', 'activeBatches', 'expiredBatches', 'damagedBatches',
            'exhaustedBatches', 'lowStockItems', 'totalValue', 
            'recentTransactions', 'expiringBatches'
        ));
    }

    // ================= BATCH MANAGEMENT =================
    public function index()
    {
        $query = InventoryBatch::with(['material', 'purchaseOrder.vendor']);
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        if (request('material')) {
            $query->whereHas('material', function($sub) {
                $sub->where('name', 'like', '%' . request('material') . '%');
            });
        }
        
        if (request('batch_number')) {
            $query->where('batch_number', 'like', '%' . request('batch_number') . '%');
        }
        
        $batches = $query->latest('created_at')->paginate(20);

        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $statuses = ['active', 'expired', 'damaged', 'exhausted'];

        return view('inventory.item.index', compact('batches', 'materials', 'statuses'));
    }

    public function create()
    {
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::with('vendor')
            ->where('status', 'approved')
            ->orderBy('po_number')
            ->get();

        return view('inventory.create', compact('materials', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'material_id' => 'required|exists:materials,id',
            'batch_number' => 'nullable|string|unique:inventory_batches,batch_number',
            'received_weight' => 'required|numeric|min:0.001',
            'received_quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'storage_location' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'supplier_batch_number' => 'nullable|string|max:255',
            'quality_grade' => 'nullable|in:A,B,C,Rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();

        try {
            // Generate batch number if not provided
            if (empty($validated['batch_number'])) {
                $validated['batch_number'] = $this->generateBatchNumber($validated['material_id']);
            }

            $batch = InventoryBatch::create([
                'purchase_order_id' => $validated['purchase_order_id'],
                'material_id' => $validated['material_id'],
                'batch_number' => $validated['batch_number'],
                'received_weight' => $validated['received_weight'],
                'received_quantity' => $validated['received_quantity'],
                'current_weight' => $validated['received_weight'],
                'current_quantity' => $validated['received_quantity'],
                'unit_price' => $validated['unit_price'],
                'storage_location' => $validated['storage_location'],
                'expiry_date' => $validated['expiry_date'],
                'supplier_batch_number' => $validated['supplier_batch_number'],
                'quality_grade' => $validated['quality_grade'] ?? 'A',
                'notes' => $validated['notes'],
                'received_date' => now()->toDateString(),
                'status' => 'active'
            ]);

            // Create intake transaction
            InventoryTransaction::create([
                'batch_id' => $batch->id,
                'type' => 'intake',
                'weight' => $validated['received_weight'],
                'quantity' => $validated['received_quantity'],
                'unit_price' => $validated['unit_price'],
                'total_value' => $validated['received_quantity'] * $validated['unit_price'],
                'transaction_date' => now(),
                'reference_number' => 'INTAKE-' . $batch->batch_number,
                'notes' => 'Initial stock intake'
            ]);

            // Generate barcode if needed
            $this->generateBarcode($batch);

            DB::commit();

            return redirect()->route('inventory.index')
                ->with('success', 'Inventory batch created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating inventory batch: ' . $e->getMessage());
        }
    }

    public function show(InventoryBatch $inventory)
    {
        $inventory->load(['material', 'purchaseOrder.vendor', 'transactions']);
        
        $transactions = $inventory->transactions()
            ->latest('transaction_date')
            ->get();

        $totalIntake = $transactions->where('type', 'intake')->sum('quantity');
        $totalDispatch = $transactions->where('type', 'dispatch')->sum('quantity');
        $totalDamage = $transactions->where('type', 'damage')->sum('quantity');
        $totalAdjustment = $transactions->where('type', 'adjustment')->sum('quantity');

        return view('inventory.show', compact(
            'inventory', 'transactions', 'totalIntake', 
            'totalDispatch', 'totalDamage', 'totalAdjustment'
        ));
    }

    public function edit(InventoryBatch $inventory)
    {
        if (in_array($inventory->status, ['exhausted', 'damaged'])) {
            return redirect()->route('inventory.index')
                ->with('error', 'Cannot edit exhausted or damaged batches.');
        }

        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::with('vendor')
            ->where('status', 'approved')
            ->orderBy('po_number')
            ->get();

        return view('inventory.edit', compact('inventory', 'materials', 'purchaseOrders'));
    }

    public function update(Request $request, InventoryBatch $inventory)
    {
        $validated = $request->validate([
            'batch_number' => [
                'required',
                'string',
                Rule::unique('inventory_batches', 'batch_number')->ignore($inventory->id)
            ],
            'storage_location' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'supplier_batch_number' => 'nullable|string|max:255',
            'quality_grade' => 'nullable|in:A,B,C,Rejected',
            'notes' => 'nullable|string|max:1000',
            'unit_price' => 'required|numeric|min:0'
        ]);

        try {
            $inventory->update($validated);

            return redirect()->route('inventory.index')
                ->with('success', 'Inventory batch updated successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating inventory batch: ' . $e->getMessage());
        }
    }

    public function destroy(InventoryBatch $inventory)
    {
        if ($inventory->transactions()->count() > 1) {
            return redirect()->route('inventory.index')
                ->with('error', 'Cannot delete batch with multiple transactions.');
        }

        try {
            DB::beginTransaction();

            // Delete related transactions
            $inventory->transactions()->delete();
            
            // Delete barcode if exists
            if ($inventory->barcode) {
                $inventory->barcode->delete();
            }

            // Delete the batch
            $inventory->delete();

            DB::commit();

            return redirect()->route('inventory.index')
                ->with('success', 'Inventory batch deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('inventory.index')
                ->with('error', 'Error deleting inventory batch: ' . $e->getMessage());
        }
    }

    // ================= MATERIAL RECEIPT =================
    public function receiveMaterial(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'material_id' => 'required|exists:materials,id',
            'received_weight' => 'required|numeric|min:0.001',
            'received_quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'storage_location' => 'nullable|string',
            'expiry_date' => 'nullable|date|after:today',
            'quality_grade' => 'nullable|in:A,B,C,Rejected',
            'supplier_batch_number' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $batchNumber = $this->generateBatchNumber($validated['material_id']);

            $batch = InventoryBatch::create([
                'purchase_order_id' => $validated['purchase_order_id'],
                'material_id' => $validated['material_id'],
                'batch_number' => $batchNumber,
                'received_weight' => $validated['received_weight'],
                'received_quantity' => $validated['received_quantity'],
                'current_weight' => $validated['received_weight'],
                'current_quantity' => $validated['received_quantity'],
                'unit_price' => $validated['unit_price'],
                'storage_location' => $validated['storage_location'],
                'expiry_date' => $validated['expiry_date'],
                'supplier_batch_number' => $validated['supplier_batch_number'],
                'quality_grade' => $validated['quality_grade'] ?? 'A',
                'notes' => $validated['notes'],
                'received_date' => now()->toDateString(),
                'status' => 'active'
            ]);

            // Create intake transaction
            InventoryTransaction::create([
                'batch_id' => $batch->id,
                'type' => 'intake',
                'weight' => $validated['received_weight'],
                'quantity' => $validated['received_quantity'],
                'unit_price' => $validated['unit_price'],
                'total_value' => $validated['received_quantity'] * $validated['unit_price'],
                'transaction_date' => now(),
                'reference_number' => 'RECEIPT-' . $batch->batch_number,
                'notes' => 'Material received from PO'
            ]);

            // Generate barcode
            $this->generateBarcode($batch);

            DB::commit();

            return back()->with('success', 'Material received and batch created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error receiving material: ' . $e->getMessage());
        }
    }

    // ================= DISPATCH MANAGEMENT =================
    public function dispatch(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:inventory_batches,id',
            'weight' => 'required|numeric|min:0.001',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'dispatch_to' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        $batch = InventoryBatch::findOrFail($validated['batch_id']);

        if ($batch->current_quantity < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock in batch! Available: ' . $batch->current_quantity);
        }

        if ($batch->status !== 'active') {
            return back()->with('error', 'Cannot dispatch from inactive batch.');
        }

        DB::beginTransaction();

        try {
            $unitPrice = $validated['unit_price'] ?? $batch->unit_price;
            
            // Create dispatch transaction
            InventoryTransaction::create([
                'batch_id' => $batch->id,
                'type' => 'dispatch',
                'weight' => $validated['weight'],
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
                'total_value' => $validated['quantity'] * $unitPrice,
                'reference_number' => $validated['reference_number'] ?? 'DISP-' . date('YmdHis'),
                'dispatch_to' => $validated['dispatch_to'],
                'purpose' => $validated['purpose'],
                'notes' => $validated['notes'],
                'transaction_date' => now()
            ]);

            // Update batch quantities
            $newQuantity = $batch->current_quantity - $validated['quantity'];
            $newWeight = $batch->current_weight - $validated['weight'];
            
            $batch->update([
                'current_weight' => max(0, $newWeight),
                'current_quantity' => $newQuantity,
                'status' => $newQuantity <= 0 ? 'exhausted' : 'active'
            ]);

            DB::commit();

            return back()->with('success', 'Material dispatched successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error during dispatch: ' . $e->getMessage());
        }
    }

    // ================= DAMAGE MANAGEMENT =================
    public function markDamaged(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:inventory_batches,id',
            'weight' => 'required|numeric|min:0.001',
            'quantity' => 'required|integer|min:1',
            'damage_type' => 'required|in:expired,contaminated,physical_damage,other',
            'reason' => 'required|string|max:500',
            'reported_by' => 'nullable|string|max:255',
            'action_taken' => 'nullable|string|max:500'
        ]);

        $batch = InventoryBatch::findOrFail($validated['batch_id']);

        if ($batch->current_quantity < $validated['quantity']) {
            return back()->with('error', 'Damage quantity exceeds available stock!');
        }

        DB::beginTransaction();

        try {
            InventoryTransaction::create([
                'batch_id' => $batch->id,
                'type' => 'damage',
                'weight' => $validated['weight'],
                'quantity' => $validated['quantity'],
                'unit_price' => $batch->unit_price,
                'total_value' => $validated['quantity'] * $batch->unit_price,
                'damage_type' => $validated['damage_type'],
                'reason' => $validated['reason'],
                'reported_by' => $validated['reported_by'],
                'action_taken' => $validated['action_taken'],
                'transaction_date' => now()
            ]);

            $newQuantity = $batch->current_quantity - $validated['quantity'];
            $newWeight = $batch->current_weight - $validated['weight'];
            
            $batch->update([
                'current_weight' => max(0, $newWeight),
                'current_quantity' => $newQuantity,
                'status' => $newQuantity <= 0 ? 'damaged' : ($batch->status === 'active' ? 'active' : $batch->status)
            ]);

            DB::commit();

            return back()->with('success', 'Damaged stock recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error recording damage: ' . $e->getMessage());
        }
    }

    // ================= STOCK ADJUSTMENT =================
    public function adjustStock(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:inventory_batches,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'weight' => 'required|numeric|min:0.001',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'authorized_by' => 'required|string|max:255'
        ]);

        $batch = InventoryBatch::findOrFail($validated['batch_id']);

        if ($validated['adjustment_type'] === 'decrease' && 
            $batch->current_quantity < $validated['quantity']) {
            return back()->with('error', 'Adjustment quantity exceeds available stock!');
        }

        DB::beginTransaction();

        try {
            $multiplier = $validated['adjustment_type'] === 'increase' ? 1 : -1;
            
            InventoryTransaction::create([
                'batch_id' => $batch->id,
                'type' => 'adjustment',
                'weight' => $validated['weight'] * $multiplier,
                'quantity' => $validated['quantity'] * $multiplier,
                'unit_price' => $batch->unit_price,
                'total_value' => ($validated['quantity'] * $batch->unit_price) * $multiplier,
                'reason' => $validated['reason'],
                'authorized_by' => $validated['authorized_by'],
                'transaction_date' => now()
            ]);

            $newQuantity = $batch->current_quantity + ($validated['quantity'] * $multiplier);
            $newWeight = $batch->current_weight + ($validated['weight'] * $multiplier);
            
            $batch->update([
                'current_weight' => max(0, $newWeight),
                'current_quantity' => max(0, $newQuantity),
                'status' => $newQuantity <= 0 ? 'exhausted' : 'active'
            ]);

            DB::commit();

            $action = $validated['adjustment_type'] === 'increase' ? 'increased' : 'decreased';
            return back()->with('success', "Stock {$action} successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error adjusting stock: ' . $e->getMessage());
        }
    }

    // ================= REPORTS =================
    public function stockReport()
    {
        $stockSummary = InventoryBatch::select(
            'material_id',
            DB::raw('SUM(current_quantity) as total_quantity'),
            DB::raw('SUM(current_weight) as total_weight'),
            DB::raw('SUM(current_quantity * unit_price) as total_value'),
            DB::raw('COUNT(*) as batch_count')
        )
        ->where('status', 'active')
        ->groupBy('material_id')
        ->with('material')
        ->get();

        return view('inventory.reports.stock', compact('stockSummary'));
    }

    public function transactionReport(Request $request)
    {
        $query = InventoryTransaction::with(['batch.material']);

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest('transaction_date')->paginate(50);

        return view('inventory.reports.transactions', compact('transactions'));
    }

    // ================= HELPER METHODS =================
    private function generateBatchNumber($materialId)
    {
        $material = Material::find($materialId);
        $prefix = strtoupper(substr($material->name, 0, 3));
        $date = date('Ymd');
        $lastBatch = InventoryBatch::where('batch_number', 'like', "{$prefix}-{$date}-%")
            ->latest('id')->first();
        
        $sequence = $lastBatch ? 
            intval(substr($lastBatch->batch_number, -3)) + 1 : 1;
        
        return $prefix . '-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    private function generateBarcode($batch)
    {
        if (class_exists('App\Models\Barcode')) {
            Barcode::create([
                'batch_id' => $batch->id,
                'material_id' => $batch->material_id,
                'purchase_id' => $batch->purchase_order_id,
                'barcode' => 'BC-' . $batch->batch_number
            ]);
        }
    }

    // ================= BATCH STATUS MANAGEMENT =================
    public function updateStatus(Request $request, InventoryBatch $inventory)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,expired,damaged,exhausted',
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $inventory->update([
                'status' => $validated['status'],
                'status_reason' => $validated['reason'],
                'status_updated_at' => now()
            ]);

            return back()->with('success', 'Batch status updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // ================= BULK OPERATIONS =================
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'batch_ids' => 'required|array',
            'batch_ids.*' => 'exists:inventory_batches,id',
            'action' => 'required|in:update_status,update_location,mark_expired',
            'status' => 'required_if:action,update_status|in:active,expired,damaged,exhausted',
            'storage_location' => 'required_if:action,update_location|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $batches = InventoryBatch::whereIn('id', $validated['batch_ids']);

            switch ($validated['action']) {
                case 'update_status':
                    $batches->update([
                        'status' => $validated['status'],
                        'status_updated_at' => now()
                    ]);
                    $message = 'Batch statuses updated successfully!';
                    break;

                case 'update_location':
                    $batches->update([
                        'storage_location' => $validated['storage_location']
                    ]);
                    $message = 'Storage locations updated successfully!';
                    break;

                case 'mark_expired':
                    $batches->update([
                        'status' => 'expired',
                        'status_updated_at' => now()
                    ]);
                    $message = 'Batches marked as expired successfully!';
                    break;
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error performing bulk operation: ' . $e->getMessage());
        }
    }
}