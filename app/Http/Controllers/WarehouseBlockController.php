<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseBlock;
use Illuminate\Http\Request;
use App\Models\WarehouseSlot;

class WarehouseBlockController extends Controller
{
 

public function index()
{
    $warehouses = Warehouse::with(['blocks' => fn($q) => $q->withCount('slots')])->get();

    return view('dashboard.warehouses.blocks.index', compact('warehouses'));
}

public function all()
{
    // For all warehouses
    $warehouses = Warehouse::with(['blocks' => fn($q) => $q->withCount('slots')])->get();

    return view('dashboard.warehouses.blocks.all', compact('warehouses'));
}
  

    // 🔹 Show form to create a new block
    public function create($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        return view('dashboard.warehouses.blocks.create', compact('warehouse'));
    }

    // 🔹 Store a new block with slots
    public function store(Request $request, $warehouseId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rows' => 'required|integer|min:1',
            'columns' => 'required|integer|min:1',
        ]);

        $warehouse = Warehouse::findOrFail($warehouseId);

        // Create the block
        $block = WarehouseBlock::create([
            'warehouse_id' => $warehouse->id,
            'name' => $request->name,
            'rows' => $request->rows,
            'columns' => $request->columns,
        ]);

        // Create slots for the block
        $slots = [];
        for ($r = 1; $r <= $block->rows; $r++) {
            for ($c = 1; $c <= $block->columns; $c++) {
                $slots[] = [
                    'block_id' => $block->id,
                    'row' => $r,
                    'column' => $c,
                    'status' => 'empty',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Bulk insert slots
        WarehouseSlot::insert($slots);

        return redirect()
            ->route('warehouses.blocks.index', $warehouse->id)
            ->with('success', 'Block created with slots successfully!');
    }

    // 🔹 Show form to edit block
    public function edit($warehouseId, $blockId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $block = WarehouseBlock::where('warehouse_id', $warehouseId)->findOrFail($blockId);
        return view('dashboard.warehouses.blocks.edit', compact('warehouse', 'block'));
    }

  // Add this method to your WarehouseBlockController

// 🔹 Show block details with slots
public function show($warehouseId, $blockId)
{
    $warehouse = Warehouse::findOrFail($warehouseId);
    $block = WarehouseBlock::where('warehouse_id', $warehouseId)
                          ->with('slots')
                          ->findOrFail($blockId);
    
    return view('dashboard.warehouses.blocks.show', compact('warehouse', 'block'));
}

public function getSlotDetails($id)
{
    $slot = WarehouseSlot::with([
        'block.warehouse',
        'batch.material'
    ])->find($id);

    if (!$slot) {
        return response()->json(['success' => false]);
    }

    return response()->json([
        'success' => true,
        'slot' => [
            'id' => $slot->id,
            'row' => $slot->row,
            'column' => $slot->column,
            'status' => $slot->status,
            'product_name' => optional($slot->batch->material)->name,
            'quantity' => optional($slot->batch)->current_quantity,
            'batch_number' => optional($slot->batch)->batch_number,
            'warehouse_name' => optional($slot->block->warehouse)->name,
            'block' => [
                'id' => optional($slot->block)->id,
                'name' => optional($slot->block)->name,
                'rows' => optional($slot->block)->rows,
                'columns' => optional($slot->block)->columns,
            ],
        ]
    ]);
}

    public function update(Request $request, $warehouseId, $blockId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'rows' => 'sometimes|integer|min:1',
                'columns' => 'sometimes|integer|min:1',
            ]);

            $warehouse = Warehouse::findOrFail($warehouseId);
            $block = WarehouseBlock::where('warehouse_id', '=', $warehouseId)->findOrFail($blockId);

            $oldRows = $block->rows;
            $oldColumns = $block->columns;

            $block->update([
                'name' => $request->name,
                'rows' => $request->rows ?? $block->rows,
                'columns' => $request->columns ?? $block->columns,
            ]);

            if ($this->dimensionsChanged($request, $block, $oldRows, $oldColumns)) {
                $this->regenerateSlots($block);
            }

            return redirect()
                ->route('warehouses.blocks.index', $warehouseId)
                ->with('success', 'Block updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Block update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update block.');
        }
    }

    private function dimensionsChanged($request, $block, $oldRows, $oldColumns)
    {
        return ($request->has('rows') || $request->has('columns')) && 
               ($block->rows != $oldRows || $block->columns != $oldColumns);
    }

    private function regenerateSlots($block)
    {
        $block->slots()->delete();
        
        $slots = [];
        for ($r = 1; $r <= $block->rows; $r++) {
            for ($c = 1; $c <= $block->columns; $c++) {
                $slots[] = [
                    'block_id' => $block->id,
                    'row' => $r,
                    'column' => $c,
                    'status' => 'empty',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        WarehouseSlot::insert($slots);
    }

    // 🔹 Delete block and its slots
    public function destroy($warehouseId, $blockId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $block = WarehouseBlock::where('warehouse_id', $warehouseId)->findOrFail($blockId);

        // Delete related slots first
        $block->slots()->delete();

        // Delete the block
        $block->delete();

        return redirect()
            ->route('warehouses.blocks.index', $warehouseId)
            ->with('success', 'Block and its slots deleted successfully!');
    }
}