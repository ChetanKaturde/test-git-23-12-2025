# Work Order Enhancement Plan
**Monitorbizz - External vs Internal Job Types**

---

## 🔍 Conflict Audit

### **Potential Conflicts & Resolutions**

| Conflict | Risk Level | Resolution |
|----------|------------|------------|
| New `work_order_type` field | LOW | Use `nullable()` with default 'external' |
| New `customer_id` field | LOW | Use `nullable()` - internal jobs don't need customers |
| Existing WO creation flow | MEDIUM | Wrap new logic in conditionals, keep old API intact |
| Invoice auto-generation | MEDIUM | Only trigger for external jobs with `if ($wo->isExternal())` |
| Machine status updates | LOW | Enhance existing logic, don't replace |
| Material consumption costing | LOW | Add new methods, keep existing consumption tracking |

### **Backward Compatibility Strategy**
- All existing work orders default to `work_order_type = 'external'`
- Existing APIs continue working unchanged
- New fields are nullable and optional
- No breaking changes to blade templates

---

## 🛠 Migration Plan

### **Database Migration**
```php
<?php
// database/migrations/2024_12_XX_enhance_work_orders_job_types.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Job type classification
            $table->enum('work_order_type', ['external', 'internal'])
                  ->default('external')
                  ->after('id');
            
            // External job fields
            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained()
                  ->after('work_order_type');
            $table->decimal('quoted_rate', 10, 2)
                  ->nullable()
                  ->after('customer_id');
            
            // Internal job fields
            $table->string('drawing_reference')
                  ->nullable()
                  ->after('quoted_rate');
            $table->string('job_reference')
                  ->nullable()
                  ->after('drawing_reference');
            $table->text('shift_instructions')
                  ->nullable()
                  ->after('job_reference');
            $table->integer('shift_target')
                  ->nullable()
                  ->comment('Target pieces per hour')
                  ->after('shift_instructions');
            
            // Enhanced tracking
            $table->decimal('material_cost', 10, 2)
                  ->default(0)
                  ->after('shift_target');
            $table->decimal('labor_cost', 10, 2)
                  ->default(0)
                  ->after('material_cost');
            $table->decimal('total_cost', 10, 2)
                  ->default(0)
                  ->after('labor_cost');
        });
        
        // Backfill existing records
        DB::table('work_orders')->update([
            'work_order_type' => 'external',
            'material_cost' => 0,
            'labor_cost' => 0,
            'total_cost' => 0
        ]);
    }

    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn([
                'work_order_type',
                'customer_id', 
                'quoted_rate',
                'drawing_reference',
                'job_reference',
                'shift_instructions',
                'shift_target',
                'material_cost',
                'labor_cost',
                'total_cost'
            ]);
        });
    }
};
```

---

## 📋 Model & Controller Updates

### **Enhanced WorkOrder Model**
```php
<?php
// app/Models/WorkOrder.php

class WorkOrder extends Model
{
    use BelongsToBusiness;

    protected $fillable = [
        'work_order_number', 'product_name', 'quantity', 'description',
        'machine_id', 'operator_id', 'status', 'started_at', 'completed_at',
        // New fields
        'work_order_type', 'customer_id', 'quoted_rate',
        'drawing_reference', 'job_reference', 'shift_instructions', 'shift_target',
        'material_cost', 'labor_cost', 'total_cost'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'quoted_rate' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Job type helpers
    public function isExternal()
    {
        return $this->work_order_type === 'external';
    }

    public function isInternal()
    {
        return $this->work_order_type === 'internal';
    }

    // Cost calculations
    public function calculateMaterialCost()
    {
        return $this->materialConsumptions->sum(function($consumption) {
            $batch = $consumption->inventoryBatch;
            $unitCost = $batch ? $batch->unit_price : $consumption->material->unit_price;
            return $consumption->actual_quantity * $unitCost;
        });
    }

    public function calculateLaborCost()
    {
        if (!$this->started_at || !$this->completed_at) return 0;
        
        $hours = $this->started_at->diffInHours($this->completed_at);
        return $hours * 150; // ₹150/hour labor rate
    }

    public function updateTotalCost()
    {
        $this->material_cost = $this->calculateMaterialCost();
        $this->labor_cost = $this->calculateLaborCost();
        $this->total_cost = $this->material_cost + $this->labor_cost;
        $this->save();
    }
}
```

### **Enhanced WorkOrderController**
```php
<?php
// app/Http/Controllers/WorkOrderController.php

class WorkOrderController extends Controller
{
    public function create()
    {
        $machines = Machine::where('business_id', auth()->user()->business_id)->get();
        $materials = Material::where('business_id', auth()->user()->business_id)->get();
        $customers = Customer::where('business_id', auth()->user()->business_id)->get();
        $operators = User::where('business_id', auth()->user()->business_id)
                        ->where('role', 'machinist')->get();

        return view('work-orders.create', compact('machines', 'materials', 'customers', 'operators'));
    }

    public function store(Request $request)
    {
        $rules = [
            'work_order_type' => 'required|in:external,internal',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'machine_id' => 'required|exists:machines,id',
            'operator_id' => 'required|exists:users,id',
        ];

        // Conditional validation based on job type
        if ($request->work_order_type === 'external') {
            $rules['customer_id'] = 'required|exists:customers,id';
            $rules['quoted_rate'] = 'required|numeric|min:0';
        } else {
            $rules['drawing_reference'] = 'required|string|max:255';
            $rules['job_reference'] = 'nullable|string|max:255';
            $rules['shift_instructions'] = 'nullable|string';
            $rules['shift_target'] = 'nullable|integer|min:1';
        }

        $validated = $request->validate($rules);
        
        $workOrder = WorkOrder::create(array_merge($validated, [
            'work_order_number' => $this->generateWorkOrderNumber(),
            'status' => 'pending',
            'business_id' => auth()->user()->business_id
        ]));

        return redirect()->route('work-orders.show', $workOrder)
                        ->with('success', 'Work order created successfully');
    }

    public function start(WorkOrder $workOrder)
    {
        // Update machine status
        $workOrder->machine->update(['status' => 'in_use']);
        
        $workOrder->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return redirect()->back()->with('success', 'Work order started');
    }

    public function complete(WorkOrder $workOrder)
    {
        DB::transaction(function() use ($workOrder) {
            // Update work order
            $workOrder->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Calculate final costs
            $workOrder->updateTotalCost();

            // Update machine status
            $workOrder->machine->update(['status' => 'available']);

            // Auto-generate invoice for external jobs
            if ($workOrder->isExternal() && $workOrder->customer_id) {
                $this->generateInvoiceFromWorkOrder($workOrder);
            }
        });

        return redirect()->back()->with('success', 'Work order completed');
    }

    private function generateInvoiceFromWorkOrder(WorkOrder $workOrder)
    {
        $invoice = Invoice::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'work_order_id' => $workOrder->id,
            'customer_id' => $workOrder->customer_id,
            'subtotal' => $workOrder->total_cost,
            'tax_amount' => $workOrder->total_cost * 0.18, // 18% GST
            'total_amount' => $workOrder->total_cost * 1.18,
            'status' => 'draft',
            'business_id' => $workOrder->business_id
        ]);

        // Create invoice item
        $invoice->items()->create([
            'description' => $workOrder->product_name,
            'quantity' => $workOrder->quantity,
            'unit_price' => $workOrder->quoted_rate ?: ($workOrder->total_cost / $workOrder->quantity),
            'total_price' => $workOrder->total_cost
        ]);
    }
}
```

---

## 🎨 Blade Form Enhancement

### **Work Order Create Form with Alpine.js**
```html
<!-- resources/views/work-orders/create.blade.php -->
<div x-data="{ jobType: 'external' }" class="container">
    <form method="POST" action="{{ route('work-orders.store') }}">
        @csrf
        
        <!-- Job Type Selection -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Job Type</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="work_order_type" value="external" 
                               x-model="jobType" id="external" checked>
                        <label class="form-check-label" for="external">
                            External Job (Customer Billing)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="work_order_type" value="internal" 
                               x-model="jobType" id="internal">
                        <label class="form-check-label" for="internal">
                            Internal Job (Workshop Use)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Fields -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
        </div>

        <!-- External Job Fields -->
        <div x-show="jobType === 'external'" class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-select" :required="jobType === 'external'">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Quoted Rate (₹)</label>
                <input type="number" name="quoted_rate" class="form-control" 
                       step="0.01" min="0" :required="jobType === 'external'">
            </div>
        </div>

        <!-- Internal Job Fields -->
        <div x-show="jobType === 'internal'" class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Drawing Reference</label>
                <input type="text" name="drawing_reference" class="form-control" 
                       :required="jobType === 'internal'">
            </div>
            <div class="col-md-6">
                <label class="form-label">Job Reference</label>
                <input type="text" name="job_reference" class="form-control" 
                       placeholder="Parent WO or Assembly">
            </div>
        </div>

        <div x-show="jobType === 'internal'" class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Shift Instructions</label>
                <textarea name="shift_instructions" class="form-control" rows="2"
                          placeholder="Handover notes for next shift"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Target (pieces/hour)</label>
                <input type="number" name="shift_target" class="form-control" min="1">
            </div>
        </div>

        <!-- Common Fields -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Machine</label>
                <select name="machine_id" class="form-select" required>
                    <option value="">Select Machine</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Operator</label>
                <select name="operator_id" class="form-select" required>
                    <option value="">Select Operator</option>
                    @foreach($operators as $operator)
                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Work Order</button>
    </form>
</div>
```

---

## 🧪 Test Plan

### **Test Cases**

#### **1. External Job Creation → Auto-Invoice**
```php
// Test: Create external WO and verify invoice generation
$customer = Customer::factory()->create(['business_id' => 1]);
$workOrder = WorkOrder::create([
    'work_order_type' => 'external',
    'customer_id' => $customer->id,
    'quoted_rate' => 500,
    'product_name' => 'Custom Brackets',
    'quantity' => 10,
    'business_id' => 1
]);

$workOrder->complete();

$this->assertDatabaseHas('invoices', [
    'work_order_id' => $workOrder->id,
    'customer_id' => $customer->id
]);
```

#### **2. Internal Job Creation → No Invoice**
```php
// Test: Create internal WO and verify no invoice
$workOrder = WorkOrder::create([
    'work_order_type' => 'internal',
    'drawing_reference' => 'DRG-001',
    'job_reference' => 'JIG-ASSEMBLY',
    'product_name' => 'Welding Jig',
    'quantity' => 1,
    'business_id' => 1
]);

$workOrder->complete();

$this->assertDatabaseMissing('invoices', [
    'work_order_id' => $workOrder->id
]);
```

#### **3. Machine Status Sync**
```php
// Test: Machine status updates on WO start/complete
$machine = Machine::factory()->create(['status' => 'available']);
$workOrder = WorkOrder::factory()->create(['machine_id' => $machine->id]);

$workOrder->start();
$this->assertEquals('in_use', $machine->fresh()->status);

$workOrder->complete();
$this->assertEquals('available', $machine->fresh()->status);
```

#### **4. Material Consumption Costing**
```php
// Test: Material cost calculation with waste
$material = Material::factory()->create(['unit_price' => 100]);
$workOrder = WorkOrder::factory()->create();

$workOrder->materialConsumptions()->create([
    'material_id' => $material->id,
    'planned_quantity' => 10,
    'actual_quantity' => 12, // 20% waste
]);

$workOrder->updateTotalCost();
$this->assertEquals(1200, $workOrder->material_cost); // 12 * 100
```

---

## 🔄 Rollback Plan

### **Emergency Rollback Steps**

#### **1. Database Rollback**
```bash
# Rollback migration
php artisan migrate:rollback --step=1

# Verify existing work orders still work
php artisan tinker
>>> WorkOrder::count()
>>> WorkOrder::first()->toArray()
```

#### **2. Code Rollback**
```php
// Remove new methods from WorkOrder model
// Comment out job type logic in controller
// Restore original blade templates

// Quick fix: Add null checks
if (method_exists($workOrder, 'isExternal')) {
    // New logic
} else {
    // Old logic
}
```

#### **3. Data Recovery**
```sql
-- If data corruption occurs
SELECT * FROM work_orders WHERE work_order_type IS NULL;
UPDATE work_orders SET work_order_type = 'external' WHERE work_order_type IS NULL;
```

---

## 🚀 Implementation Timeline

### **Phase 1: Database & Models (Day 1)**
- Run migration
- Update WorkOrder model
- Test basic CRUD operations

### **Phase 2: Controller Logic (Day 2)**
- Enhance WorkOrderController
- Add validation rules
- Test job type creation

### **Phase 3: UI Enhancement (Day 3)**
- Update create form with Alpine.js
- Add conditional field display
- Test user experience

### **Phase 4: Integration Testing (Day 4)**
- Test external job → invoice flow
- Test internal job tracking
- Test machine status sync
- Test material costing

### **Phase 5: Production Deployment (Day 5)**
- Deploy to staging
- Run full test suite
- Deploy to production
- Monitor for issues

This enhancement maintains full backward compatibility while adding powerful job type differentiation for manufacturing workflows.