<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = WorkOrder::where('business_id', '=', $user->business_id)
            ->with(['machine', 'operator', 'assignedTo', 'materialConsumptions.material']);
        
        // Filter for operators - show only assigned work orders
        if ($user->role === 'operator') {
            $query->where('assigned_to', $user->id);
        }
        
        $workOrders = $query->latest()->get();
        
        // Get operators for assignment dropdown (managers and admins only)
        $operators = collect();
        if (in_array($user->role, ['admin', 'manager'])) {
            $operators = \App\Models\User::where('business_id', $user->business_id)
                ->where('role', 'operator')
                ->where('is_active', true)
                ->get();
        }
        
        return view('work-orders.index', compact('workOrders', 'operators'));
    }

    public function create()
    {
        $machines = Machine::where('business_id', '=', auth()->user()->business_id)->get();
        $customers = \App\Models\Customer::where('business_id', '=', auth()->user()->business_id)
            ->where('is_active', true)
            ->get();
        return view('work-orders.create', compact('machines', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wo_number' => 'required|string|max:50|unique:work_orders',
            'machine_id' => 'required|exists:machines,id',
            'customer_id' => 'nullable|exists:customers,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'quoted_rate' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['business_id'] = auth()->user()->business_id;
        $validated['operator_id'] = auth()->id();
        $validated['status'] = 'pending';

        WorkOrder::create($validated);

        return redirect()->route('work-orders.index')->with('success', 'Work order created successfully!');
    }

    public function show(WorkOrder $workOrder)
    {
        return view('work-orders.show', compact('workOrder'));
    }

    public function start(WorkOrder $workOrder)
    {
        // Validate inventory availability before starting
        $inventoryCheck = $this->validateInventoryAvailability($workOrder);
        if (!$inventoryCheck['success']) {
            return redirect()->back()->with('error', $inventoryCheck['message']);
        }

        DB::transaction(function () use ($workOrder) {
            // Update work order status
            $workOrder->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);

            // Update machine status to 'in_use'
            if ($workOrder->machine) {
                $workOrder->machine->update(['status' => 'in_use']);
            }
        });

        return redirect()->back()->with('success', 'Work order started! Machine status updated to "In Use".');
    }

    /**
     * Validate inventory availability for work order materials
     */
    private function validateInventoryAvailability(WorkOrder $workOrder)
    {
        $shortages = [];
        
        foreach ($workOrder->materialConsumptions as $consumption) {
            $availableQty = \App\Models\InventoryBatch::where('material_id', $consumption->material_id)
                ->where('current_quantity', '>', 0)
                ->where('status', 'active')
                ->sum('current_quantity');
                
            if ($availableQty < $consumption->planned_quantity) {
                $shortages[] = [
                    'material' => $consumption->material->name,
                    'required' => $consumption->planned_quantity,
                    'available' => $availableQty,
                    'shortage' => $consumption->planned_quantity - $availableQty
                ];
            }
        }
        
        if (!empty($shortages)) {
            $message = 'Insufficient inventory for: ';
            foreach ($shortages as $shortage) {
                $message .= "{$shortage['material']} (need {$shortage['required']}, have {$shortage['available']}), ";
            }
            return ['success' => false, 'message' => rtrim($message, ', ')];
        }
        
        return ['success' => true, 'message' => 'Inventory validated successfully'];
    }

    public function complete(WorkOrder $workOrder)
    {
        DB::transaction(function () use ($workOrder) {
            // Update work order status
            $workOrder->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Update machine status back to 'available'
            if ($workOrder->machine) {
                $workOrder->machine->update(['status' => 'available']);
            }

            // Deduct materials from inventory (FIFO)
            $this->deductFromInventory($workOrder);

            // Auto-generate invoice if customer is linked
            if ($workOrder->customer_id && !$workOrder->invoice) {
                $this->createInvoiceFromWorkOrder($workOrder);
            }
        });

        $message = 'Work order completed! Machine available, inventory updated.';
        if ($workOrder->customer_id && $workOrder->invoice) {
            $message .= ' Invoice auto-generated.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function assign(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Verify the assigned user is an operator in the same business
        $assignedUser = \App\Models\User::find($validated['assigned_to']);
        if ($assignedUser->business_id !== auth()->user()->business_id || $assignedUser->role !== 'operator') {
            return redirect()->back()->with('error', 'Invalid operator selected.');
        }

        $workOrder->update(['assigned_to' => $validated['assigned_to']]);

        return redirect()->back()->with('success', "Work order assigned to {$assignedUser->name} successfully!");
    }

    public function addMaterial(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'planned_quantity' => 'required|numeric|min:0',
            'actual_quantity' => 'required|numeric|min:0',
            'waste_quantity' => 'nullable|numeric|min:0',
        ]);

        $validated['work_order_id'] = $workOrder->id;
        $validated['business_id'] = auth()->user()->business_id;
        $validated['waste_quantity'] = $validated['waste_quantity'] ?? 0;

        \App\Models\MaterialConsumption::create($validated);

        return redirect()->back()->with('success', 'Material consumption recorded successfully!');
    }

    public function removeMaterial(WorkOrder $workOrder, \App\Models\MaterialConsumption $consumption)
    {
        if ($consumption->work_order_id !== $workOrder->id) {
            return redirect()->back()->with('error', 'Invalid material consumption record.');
        }

        $consumption->delete();

        return redirect()->back()->with('success', 'Material consumption removed successfully!');
    }

    /**
     * Deduct materials from inventory using FIFO method
     */
    private function deductFromInventory(WorkOrder $workOrder)
    {
        $batchUpdates = [];
        $transactions = [];
        
        foreach ($workOrder->materialConsumptions as $consumption) {
            $remainingToDeduct = $consumption->actual_quantity;
            
            // Get inventory batches for this material (FIFO - oldest first)
            $batches = \App\Models\InventoryBatch::where('material_id', '=', $consumption->material_id)
                ->where('current_quantity', '>', 0)
                ->where('status', '=', 'active')
                ->orderBy('received_date', 'asc')
                ->get();

            foreach ($batches as $batch) {
                if ($remainingToDeduct <= 0) break;

                $deductAmount = min($remainingToDeduct, $batch->current_quantity);
                
                // Prepare batch update
                $newCurrentQty = $batch->current_quantity - $deductAmount;
                $newRemainingQty = $batch->remaining_quantity - $deductAmount;
                $newStatus = $newCurrentQty <= 0 ? 'exhausted' : $batch->status;
                
                $batchUpdates[] = [
                    'id' => $batch->id,
                    'current_quantity' => $newCurrentQty,
                    'remaining_quantity' => $newRemainingQty,
                    'status' => $newStatus
                ];
                
                // Prepare transaction record
                $transactions[] = [
                    'batch_id' => $batch->id,
                    'type' => 'consumption',
                    'quantity' => $deductAmount,
                    'reference_type' => 'work_order',
                    'reference_id' => $workOrder->id,
                    'notes' => "Material consumed for WO #{$workOrder->wo_number}",
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $remainingToDeduct -= $deductAmount;
            }
            
            if ($remainingToDeduct > 0) {
                \Log::warning("Insufficient inventory for material {$consumption->material->name}. Short by {$remainingToDeduct} units.");
            }
        }
        
        // Bulk update batches
        foreach ($batchUpdates as $update) {
            \App\Models\InventoryBatch::where('id', $update['id'])->update([
                'current_quantity' => $update['current_quantity'],
                'remaining_quantity' => $update['remaining_quantity'],
                'status' => $update['status']
            ]);
        }
        
        // Bulk insert transactions
        if (!empty($transactions)) {
            \App\Models\InventoryTransaction::insert($transactions);
        }
    }

    /**
     * Create invoice from completed work order
     */
    private function createInvoiceFromWorkOrder(WorkOrder $workOrder)
    {
        $customer = $workOrder->customer;
        if (!$customer) return;

        $totalAmount = $workOrder->calculateCost();
        $taxAmount = $totalAmount * 0.18; // 18% GST
        $subtotal = $totalAmount - $taxAmount;

        $invoice = \App\Models\Invoice::create([
            'business_id' => $workOrder->business_id,
            'work_order_id' => $workOrder->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'customer_address' => $customer->address,
            'customer_gstin' => $customer->gstin,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'status' => 'draft',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'invoice_number' => $this->generateInvoiceNumber(),
        ]);

        // Create invoice item
        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => "{$workOrder->product_name} (WO: {$workOrder->wo_number})",
            'quantity' => $workOrder->quantity,
            'unit_price' => $subtotal / $workOrder->quantity,
            'tax_rate' => 18,
            'total_amount' => $totalAmount,
        ]);
    }

    private function generateInvoiceNumber(): string
    {
        $businessId = auth()->user()->business_id;
        $lastInvoice = \App\Models\Invoice::where('business_id', $businessId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $nextNumber = 1;
        if ($lastInvoice && $lastInvoice->invoice_number) {
            $parts = explode('-', $lastInvoice->invoice_number);
            if (count($parts) >= 3) {
                $nextNumber = intval(end($parts)) + 1;
            }
        }
        
        return 'INV-' . now()->format('Ym') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}