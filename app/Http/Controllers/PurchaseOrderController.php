<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{PurchaseOrder, PurchaseOrderItem, Vendor, Material, InventoryBatch, Warehouse};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Cache, Log, Auth};
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Notifications\PurchaseOrderCreated;
use App\Notifications\PurchaseOrderUpdated;
use App\Notifications\PurchaseOrderDeleted;
use App\Models\User;
use App\Models\MaterialVendor;


class PurchaseOrderController extends Controller
{
    private const CACHE_DURATION = 60;

    private const BASE_RULES = [
        'vendor_id' => 'required|exists:vendors,id',
        'po_date' => 'required|date',
        'status' => 'nullable|in:pending,approved,received,completed',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string|max:255',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'items.*.unit_price' => 'required|numeric|min:0.01',
        'items.*.description' => 'nullable|string',
    ];

    public function index(Request $request)
    {
        $orders = PurchaseOrder::with('vendor')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();
        
        return view('purchase_orders.index', compact('orders'));
    }

public function approve(Request $request, PurchaseOrder $purchaseOrder)
{
    // Ensure the purchase order belongs to the current user's business
    if ($purchaseOrder->business_id !== auth()->user()->business_id) {
        abort(404);
    }
    
    if ($purchaseOrder->status !== 'pending') {
        return back()->with('error', 'Purchase order cannot be approved.');
    }

    DB::transaction(function () use ($purchaseOrder) {
        // Update PO status
        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Create inventory batches for each item
        foreach ($purchaseOrder->items as $item) {
            $this->createInventoryBatch($purchaseOrder, $item);
        }
    });

    // Mark notification as read
    if ($request->filled('notification_id')) {
        Auth::user()->notifications()->where('id', $request->notification_id)->first()?->markAsRead();
    }

    return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                     ->with('success', 'Purchase order approved and inventory batches created.');
}

    public function create()
    {
        $vendors = Vendor::where('business_id', auth()->user()->business_id)->get();
        $materials = Material::where('business_id', auth()->user()->business_id)->get();
        return view('purchase_orders.create', compact('vendors', 'materials'));
    }

public function store(Request $request)
{
    $validated = $request->validate(self::BASE_RULES);

    // Custom validation for available quantities
    $this->validateMaterialQuantities($validated['items']);

    $order = null;

    DB::transaction(function () use ($validated, &$order) {
        $poNumber = $this->generatePoNumber();

        // Calculate total amounts
        $totalAmount = 0;
        $totalGst = 0;

        foreach ($validated['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemGst = ($itemTotal * $item['gst_rate']) / 100;
            $totalAmount += $itemTotal;
            $totalGst += $itemGst;
        }

        $finalAmount = $totalAmount + $totalGst;

        // Create the PO (status is hardcoded to 'pending')
        $order = PurchaseOrder::create([
            'vendor_id' => $validated['vendor_id'],
            'po_date' => $validated['po_date'] ?? now()->format('Y-m-d'),
            'status' => 'pending', // force status to pending
            'notes' => $validated['notes'] ?? null,
            'po_number' => $poNumber,
            'total_amount' => $totalAmount,
            'business_id' => auth()->user()->business_id,
        ]);

        // Save PO items
        foreach ($validated['items'] as $item) {
            $this->handleItemCreation($order, $item, $validated['vendor_id']);
        }
    });

    // Notify Admins AFTER transaction
    $creator = Auth::user();
    $admins = User::where('role', 'admin')->get();

    foreach ($admins as $admin) {
        $admin->notify(new PurchaseOrderCreated($order->id, $creator->name));
    }

    return redirect()->route('purchase-orders.index')->with('success', 'Purchase order created successfully.');
}


    public function edit($id)
    {
        $businessId = auth()->user()->business_id;
        $purchaseOrder = PurchaseOrder::with('items')->where('business_id', $businessId)->findOrFail($id);
        $vendors = Vendor::where('business_id', $businessId)->get();
        $materials = Material::where('business_id', $businessId)->select('id', 'name', 'code', 'unit_price', 'unit', 'gst_rate', 'vendor_id')->get();

        return view('purchase_orders.edit', compact('purchaseOrder', 'vendors', 'materials'));
    }

    public function update(Request $request, $id)
    {
        // Step 1: Validate input including nested item rules
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'po_date' => 'required|date',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $id) {
            // Step 2: Fetch the purchase order with items
            $businessId = auth()->user()->business_id;
            $order = PurchaseOrder::with('items')->where('business_id', $businessId)->findOrFail($id);

            // Step 3: Simply delete old items (NO stock restoration needed)
            $order->items()->delete();

            // Step 4: Validate stock availability
            $this->validateMaterialQuantities($validated['items']);

            // Step 5: Calculate totals
            $totalAmount = 0;
            $totalGst = 0;

            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $itemGst = ($itemTotal * $item['gst_rate']) / 100;
                $totalAmount += $itemTotal;
                $totalGst += $itemGst;
            }

            // Step 6: Update the purchase order
            $order->update([
                'vendor_id'         => $validated['vendor_id'],
                'po_date'           => $validated['po_date'] ?? $order->po_date,
                'status'            => $validated['status'] ?? $order->status,
                'notes'             => $validated['notes'],
                'total_amount'      => $totalAmount,
            ]);

            // Step 7: Create new items WITHOUT modifying material_vendor quantities
            foreach ($validated['items'] as $item) {
                $this->handleItemCreation($order, $item, $validated['vendor_id']);
            }
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order updated successfully.');
    }


public function destroy($id)
{
    DB::transaction(function () use ($id) {
        $businessId = auth()->user()->business_id;
        $order = PurchaseOrder::with('items')->where('business_id', $businessId)->findOrFail($id);

        // Notify admin users before deletion
        $this->notifyAdminsOfDeletion($order);

        // 🟢 Revert material quantities back to vendor stock
        foreach ($order->items as $item) {
            $materialVendor = \App\Models\MaterialVendor::where('material_id', $item->material_id)
                ->where('vendor_id', $order->vendor_id)
                ->first();

            if ($materialVendor) {
                $materialVendor->quantity += $item->quantity;
                $materialVendor->save();
            }
        }

        // 🧹 Delete related notifications (from previous creation/update)
        $poUrl = route('purchase-orders.show', $order->id);

        DB::table('notifications')
            ->whereJsonContains('data->url', $poUrl)
            ->delete();

        // ❌ Delete PO items and the order
        $order->items()->delete();
        $order->delete();
    });

    return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully.');
}




  
public function show($id)
{
    // Eager load items with material and vendor
    $businessId = auth()->user()->business_id;
    $purchaseOrder = PurchaseOrder::with(['items.material', 'vendor'])->where('business_id', $businessId)->findOrFail($id);

    return view('purchase_orders.show', compact('purchaseOrder'));
}

/**
 * Mark PO as received and create inventory batches
 */
public function receive(Request $request, PurchaseOrder $purchaseOrder)
{
    // Ensure the purchase order belongs to the current user's business
    if ($purchaseOrder->business_id !== auth()->user()->business_id) {
        abort(404);
    }
    
    if ($purchaseOrder->status !== 'approved') {
        return back()->with('error', 'Only approved purchase orders can be received.');
    }

    DB::transaction(function () use ($purchaseOrder) {
        // Update PO status
        $purchaseOrder->update([
            'status' => 'received',
            'received_by' => Auth::id(),
            'received_at' => now(),
        ]);

        // Create inventory batches for each item
        foreach ($purchaseOrder->items as $item) {
            $this->createInventoryBatchFromPO($purchaseOrder, $item);
        }
    });

    return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                     ->with('success', 'Purchase order received. Inventory batches created successfully.');
}







    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('status', '=', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', '=', $request->vendor_id);
        }

        if ($request->filled('po_date')) {
            $query->whereDate('po_date', '=', $request->po_date);
        }
    }

    private function generatePoNumber(): string
    {
        $businessId = auth()->user()->business_id;
        $lastPO = PurchaseOrder::where('business_id', $businessId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Extract the last number from the PO number if it exists
        $nextNumber = 1;
        if ($lastPO && $lastPO->po_number) {
            $parts = explode('-', $lastPO->po_number);
            if (count($parts) >= 3) {
                $nextNumber = intval(end($parts)) + 1;
            }
        }
        
        return 'PO-' . now()->format('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

 private function validateMaterialQuantities(array $items): void
{
    // Simple validation for basic PO items
    $errors = [];
    foreach ($items as $index => $item) {
        if (empty($item['item_name'])) {
            $errors["items.{$index}.item_name"] = "Item name is required.";
        }
        
        if (empty($item['quantity']) || $item['quantity'] <= 0) {
            $errors["items.{$index}.quantity"] = "Quantity must be greater than 0.";
        }
        
        if (empty($item['unit_price']) || $item['unit_price'] <= 0) {
            $errors["items.{$index}.unit_price"] = "Unit price must be greater than 0.";
        }
    }

    if (!empty($errors)) {
        throw \Illuminate\Validation\ValidationException::withMessages($errors);
    }
}


    /**
     * Debug method to check material availability - USE THIS TO DEBUG
     * Add this route: Route::get('/debug-material/{materialId}/{vendorId}', [PurchaseOrderController::class, 'debugMaterialAvailability']);
     */
    public function debugMaterialAvailability($materialId, $vendorId)
    {
        $materialVendor = \App\Models\MaterialVendor::where('material_id', $materialId)
            ->where('vendor_id', $vendorId)
            ->first();

        if (!$materialVendor) {
            return response()->json(['error' => 'Material not found for vendor']);
        }

        $availableQty = $materialVendor->quantity;

        // Get all existing orders for this material from this vendor
        $existingOrders = \App\Models\PurchaseOrderItem::with('purchaseOrder')
            ->whereHas('purchaseOrder', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId)
                      ->whereNotIn('status', ['cancelled', 'rejected', 'completed']);
            })
            ->where('material_id', $materialId)
            ->get();

        $totalOrdered = $existingOrders->sum('quantity');
        $remainingQty = max(0, $availableQty - $totalOrdered);

        // Get material name
        $material = Material::find($materialId);
        $materialName = $material ? $material->name : 'Unknown';

        return response()->json([
            'material_name' => $materialName,
            'material_id' => $materialId,
            'vendor_id' => $vendorId,
            'available_qty' => $availableQty,
            'total_ordered' => $totalOrdered,
            'remaining_qty' => $remainingQty,
            'existing_orders' => $existingOrders->map(function ($item) {
                return [
                    'po_id' => $item->purchaseOrder->id,
                    'po_number' => $item->purchaseOrder->po_number,
                    'status' => $item->purchaseOrder->status,
                    'quantity' => $item->quantity,
                    'created_at' => $item->created_at,
                ];
            }),
        ]);
    }

    /**
     * TEMPORARY: Disable validation for testing
     * Replace validateMaterialQuantities with this method temporarily
     */
    private function validateMaterialQuantitiesDisabled(array $items): void
    {
        $vendorId = request()->input('vendor_id');
        
        foreach ($items as $index => $item) {
            $materialId = $item['material_id'];
            $requestedQty = $item['quantity'];

            $materialVendor = \App\Models\MaterialVendor::where('material_id', $materialId)
                ->where('vendor_id', $vendorId)
                ->first();

            if (!$materialVendor) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "items.{$index}.material_id" => "Material not found for selected vendor."
                ]);
            }

            // Get material name
            $material = Material::find($materialId);
            $materialName = $material ? $material->name : "Material ID {$materialId}";

            // Just log, don't validate
            \Log::info("🔍 DEBUG — Material: {$materialName}, Vendor: {$vendorId}, Available: {$materialVendor->quantity}, Requested: {$requestedQty}");
        }
    }

    /**
     * Handle item creation - ONLY updates purchase_order_items table
     * Does NOT modify material_vendor quantities
     */
  private function handleItemCreation(PurchaseOrder $order, array $item, int $vendorId): void
{
    $requestedQty = $item['quantity'];
    $unitPrice = $item['unit_price'];
    $totalAmount = $requestedQty * $unitPrice;

    $order->items()->create([
        'item_name'         => $item['item_name'],
        'description'       => $item['description'] ?? null,
        'quantity'          => $requestedQty,
        'unit_price'        => $unitPrice,
        'total_price'       => $totalAmount,
    ]);
}

  public function generatePdf($id)
{
    $purchaseOrder = PurchaseOrder::with('vendor', 'items')->findOrFail($id);

    $pdf = Pdf::loadView('purchase_orders.pdf', compact('purchaseOrder'));

    return $pdf->download("PurchaseOrder_{$purchaseOrder->po_number}.pdf");
}

    /**
     * Create inventory batch when PO is approved
     */
    private function createInventoryBatch(PurchaseOrder $purchaseOrder, $item)
    {
        // Generate batch number
        $batchNumber = 'BATCH-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Get first available warehouse
        $warehouseId = \App\Models\Warehouse::first()?->id ?? 1;
        
        \App\Models\InventoryBatch::create([
            'batch_number' => $batchNumber,
            'purchase_order_id' => $purchaseOrder->id,
            'material_id' => $item->material_id,
            'warehouse_id' => $warehouseId,
            'ordered_quantity' => $item->quantity,
            'received_quantity' => $item->quantity,
            'current_quantity' => $item->quantity,
            'remaining_quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'received_by' => Auth::id(),
            'received_date' => now(),
            'status' => 'received',
            'notes' => "Auto-created from PO approval: {$purchaseOrder->po_number}",
        ]);
        
        Log::info("Inventory batch created for PO {$purchaseOrder->po_number}, Material ID: {$item->material_id}, Quantity: {$item->quantity}");
    }

    /**
     * Create inventory batch from PO item
     */
    private function createInventoryBatchFromPO(PurchaseOrder $purchaseOrder, $poItem)
    {
        // Generate batch number
        $batchNumber = 'BATCH-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Get first available warehouse
        $warehouseId = \App\Models\Warehouse::first()?->id ?? null;
        
        // Try to find material by item name since material_id might not exist
        $materialId = $this->getMaterialIdFromItem($poItem);
        
        if ($materialId) {
            \App\Models\InventoryBatch::create([
                'business_id' => $purchaseOrder->business_id,
                'batch_number' => $batchNumber,
                'purchase_order_id' => $purchaseOrder->id,
                'material_id' => $materialId,
                'warehouse_id' => $warehouseId,
                'received_quantity' => $poItem->quantity,
                'received_weight' => $poItem->quantity, // Default to same as quantity
                'current_quantity' => $poItem->quantity,
                'current_weight' => $poItem->quantity,
                'unit_price' => $poItem->unit_price,
                'received_date' => now(),
                'status' => 'active',
                'notes' => "Received from PO: {$purchaseOrder->po_number}",
            ]);
            
            Log::info("Inventory batch created for PO {$purchaseOrder->po_number}, Item: {$poItem->item_name}, Qty: {$poItem->quantity}");
        }
    }

    private function getMaterialIdFromItem($poItem)
    {
        // Try to find material by item name
        $material = \App\Models\Material::where('business_id', auth()->user()->business_id)
            ->where('name', 'LIKE', '%' . $poItem->item_name . '%')
            ->first();
            
        return $material?->id;
    }

    /**
     * Notify admin users of purchase order deletion
     */
    private function notifyAdminsOfDeletion(PurchaseOrder $order): void
    {
        $admins = User::where('role', '=', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PurchaseOrderDeleted($order, Auth::user()));
        }
    }
}