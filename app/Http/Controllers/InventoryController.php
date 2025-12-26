<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryBatch;

class InventoryController extends Controller
{
    public function index()
    {
        try {
            $businessId = auth()->user()->business_id;
            
            $inventory = InventoryBatch::with('material')
                ->whereHas('material', function($query) use ($businessId) {
                    $query->where('business_id', '=', $businessId);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('inventory.index', compact('inventory'));
        } catch (\Exception $e) {
            \Log::error('Inventory index error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load inventory.');
        }
    }
}