<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryBatch;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InventoryBatchSeeder extends Seeder
{
    public function run(): void
    {
        $purchaseOrderItems = PurchaseOrderItem::all();

        foreach ($purchaseOrderItems as $item) {

            // Only process items with valid material_id (6 to 10)
            if ($item->material_id < 6 || $item->material_id > 10) {
                continue;
            }

            // Only process items with valid vendor_id (10 to 14)
            if (isset($item->vendor_id) && ($item->vendor_id < 10 || $item->vendor_id > 14)) {
                continue;
            }

            $batchNumber = $item->batch_number ?? 'BATCH-' . strtoupper(Str::random(8));

            $existing = InventoryBatch::where('batch_number', $batchNumber)->first();
            if ($existing) {
                continue;
            }

            InventoryBatch::create([
                'purchase_order_id' => $item->purchase_order_id,
                'material_id' => $item->material_id,
                'batch_number' => $batchNumber,
                'received_weight' => $item->weight * $item->quantity,
                'received_quantity' => $item->quantity,
                'current_weight' => $item->weight * $item->quantity,
                'current_quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'storage_location' => 'Main Warehouse',
                'received_date' => $item->created_at ? $item->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                'expiry_date' => $item->expiry_date ?? Carbon::now()->addYear()->format('Y-m-d'),
                'supplier_batch_number' => $batchNumber,
                'quality_grade' => 'A',
                'notes' => 'Initial stock from purchase order',
                'status' => 'active',
            ]);
        }
    }
}
