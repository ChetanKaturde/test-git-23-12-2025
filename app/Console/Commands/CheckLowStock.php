<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Material;
use App\Models\InventoryBatch;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Support\Facades\Log;

class CheckLowStock extends Command
{
    protected $signature = 'inventory:check-low-stock';
    protected $description = 'Check for low stock materials and send alerts';

    public function handle()
    {
        $this->info('Checking for low stock materials...');
        
        $lowStockMaterials = [];
        
        // Get all active materials
        $materials = Material::where('status', 'active')->get();
        
        foreach ($materials as $material) {
            $currentStock = InventoryBatch::where('material_id', $material->id)
                ->where('status', 'active')
                ->sum('current_quantity');
                
            $reorderPoint = $material->reorder_point ?? 10; // Default reorder point
            
            if ($currentStock <= $reorderPoint && $currentStock > 0) {
                $lowStockMaterials[] = [
                    'material' => $material,
                    'current_stock' => $currentStock,
                    'reorder_point' => $reorderPoint
                ];
            }
        }
        
        if (!empty($lowStockMaterials)) {
            $this->info('Found ' . count($lowStockMaterials) . ' low stock materials');
            
            // Notify admins and inventory managers
            $users = User::whereIn('role', ['admin', 'inventory_manager'])
                ->where('is_active', true)
                ->get();
                
            foreach ($users as $user) {
                $user->notify(new LowStockAlert($lowStockMaterials));
            }
            
            Log::info('Low stock alerts sent', ['materials_count' => count($lowStockMaterials)]);
        } else {
            $this->info('No low stock materials found');
        }
        
        return 0;
    }
}