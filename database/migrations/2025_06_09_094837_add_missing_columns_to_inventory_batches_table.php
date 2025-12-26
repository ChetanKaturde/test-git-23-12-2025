<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // Update batch
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
};
