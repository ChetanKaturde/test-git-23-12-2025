<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Machine;
use App\Models\WorkOrder;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $businessId = auth()->user()->business_id;
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Search Materials
        if (auth()->user()->canViewModule('materials')) {
            $materials = Material::where('business_id', $businessId)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%")
                      ->orWhere('sku', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($materials as $material) {
                $results[] = [
                    'type' => 'material',
                    'title' => $material->name,
                    'subtitle' => "Code: {$material->code}",
                    'url' => route('materials.show', $material),
                    'icon' => 'fas fa-cube'
                ];
            }
        }

        // Search Machines
        if (auth()->user()->canViewModule('machines')) {
            $machines = Machine::where('business_id', $businessId)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($machines as $machine) {
                $results[] = [
                    'type' => 'machine',
                    'title' => $machine->name,
                    'subtitle' => "Status: " . ucfirst($machine->status),
                    'url' => route('machines.show', $machine),
                    'icon' => 'fas fa-cogs'
                ];
            }
        }

        // Search Work Orders
        if (auth()->user()->canViewModule('work_orders')) {
            $workOrderQuery = WorkOrder::where('business_id', $businessId)
                ->where(function($q) use ($query) {
                    $q->where('wo_number', 'LIKE', "%{$query}%")
                      ->orWhere('product_name', 'LIKE', "%{$query}%");
                });

            // Filter for operators
            if (auth()->user()->role === 'operator') {
                $workOrderQuery->where('assigned_to', auth()->id());
            }

            $workOrders = $workOrderQuery->limit(5)->get();

            foreach ($workOrders as $workOrder) {
                $results[] = [
                    'type' => 'work_order',
                    'title' => $workOrder->wo_number,
                    'subtitle' => $workOrder->product_name,
                    'url' => route('work-orders.show', $workOrder),
                    'icon' => 'fas fa-clipboard-list'
                ];
            }
        }

        // Search Purchase Orders
        if (auth()->user()->canViewModule('purchase_orders')) {
            $purchaseOrders = PurchaseOrder::where('business_id', $businessId)
                ->where('po_number', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($purchaseOrders as $po) {
                $results[] = [
                    'type' => 'purchase_order',
                    'title' => $po->po_number,
                    'subtitle' => "Amount: ₹" . number_format($po->total_amount, 2),
                    'url' => route('purchase-orders.show', $po),
                    'icon' => 'fas fa-shopping-cart'
                ];
            }
        }

        // Search Vendors
        if (auth()->user()->canViewModule('vendors')) {
            $vendors = Vendor::where('business_id', $businessId)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($vendors as $vendor) {
                $results[] = [
                    'type' => 'vendor',
                    'title' => $vendor->name,
                    'subtitle' => $vendor->email,
                    'url' => route('vendors.show', $vendor),
                    'icon' => 'fas fa-truck'
                ];
            }
        }

        // Search Team Members (Admin/Manager only)
        if (in_array(auth()->user()->role, ['admin', 'manager'])) {
            $users = User::where('business_id', $businessId)
                ->where('id', '!=', auth()->id())
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(3)
                ->get();

            foreach ($users as $user) {
                $results[] = [
                    'type' => 'user',
                    'title' => $user->name,
                    'subtitle' => ucfirst($user->role),
                    'url' => '#',
                    'icon' => 'fas fa-user'
                ];
            }
        }

        return response()->json([
            'results' => array_slice($results, 0, 15),
            'total' => count($results)
        ]);
    }
}