# Monitorbizz: SME Use Case & Logic Audit

## 1. Example Business Overview

**Business Name:** Priya Fabrication Works  
**Location:** Pune, Maharashtra  
**Industry:** Metal Fabrication & Custom Manufacturing  
**Size:** 8 workers, 3 machines  
**Products:** Custom aluminum parts, brackets, enclosures  

### Current Setup:
- **Machines:** CNC Lathe (M0001), MIG Welder (M0002), Plasma Cutter (M0003)
- **Team Structure:**
  - 1 Owner/Admin (Priya)
  - 1 Manager (Supervisor)
  - 2 Machine Operators
  - 1 Inventory Clerk
  - 3 General Workers

### Business Challenges:
- Manual tracking of material consumption
- No visibility into machine utilization
- Difficulty in accurate job costing
- GST compliance for invoicing
- Vendor payment tracking

## 2. How the Business Uses Each Module

### 2.1 Registration & Setup
```
1. Priya registers at monitorbizz.com
2. Creates business: "Priya Fabrication Works"
3. Gets subdomain: priya-fab.monitorbizz.com
4. Sets up company address and GST details
```

### 2.2 Team Management
```
Admin (Priya):
- Full system access
- Financial oversight
- Vendor negotiations

Manager (Rajesh):
- Production planning
- Work order creation
- Quality control

Operators (Amit, Suresh):
- Machine operation logs
- Material consumption entry
- Job completion updates

Inventory Clerk (Meera):
- Stock management
- Purchase order processing
- Goods receipt
```

### 2.3 Materials & Inventory Setup
```
Materials Added:
- Aluminum Sheet 3mm (AL3MM001) - ₹180/kg
- Aluminum Rod 25mm (AL25RD002) - ₹220/kg  
- MIG Wire 1.2mm (MIGW1203) - ₹450/kg
- Cutting Fluid (CUTFL004) - ₹120/ltr

Units: kg, ltr, pcs, mtr
GST Rates: 18% (standard), 5% (some raw materials)
```

### 2.4 Vendor Management
```
Vendors Added:
1. Mumbai Metals Ltd
   - Materials: Aluminum sheets, rods
   - Payment Terms: 30 days
   - MOQ: 100kg minimum

2. Welding Supplies Co
   - Materials: MIG wire, electrodes
   - Payment Terms: 15 days
   - MOQ: 25kg minimum

3. Industrial Fluids Pvt Ltd
   - Materials: Cutting fluids, coolants
   - Payment Terms: Cash on delivery
   - MOQ: 50 liters
```

## 3. End-to-End Workflow (Procurement → Production → Dispatch)

### Phase 1: Procurement Workflow
```
1. Inventory Check
   - Meera checks stock levels
   - Identifies low stock items (< 10kg aluminum)

2. Purchase Order Creation
   - Creates PO-2024-001 for Mumbai Metals
   - Orders: 200kg Aluminum Sheet 3mm
   - Total: ₹36,000 + ₹6,480 GST = ₹42,480

3. PO Approval & Sending
   - Priya approves the PO
   - Email sent to vendor automatically
   - Status: "Sent to Vendor"

4. Goods Receipt
   - Material arrives after 5 days
   - Meera creates GRN-2024-001
   - Actual received: 198kg (2kg shortage)
   - Quality check: Grade A
   - Creates inventory batch: BATCH-AL-001
```

### Phase 2: Production Workflow
```
1. Work Order Creation
   - Customer order: 50 custom brackets
   - Rajesh creates WO-2024-015
   - Assigns: CNC Lathe (M0001), Operator: Amit
   - Material requirement: 25kg Aluminum Sheet

2. Production Start
   - Amit logs into machine M0001
   - Starts work order WO-2024-015
   - System records start time: 09:30 AM

3. Material Consumption
   - Amit records material usage:
     - Planned: 25kg aluminum
     - Actual used: 26.5kg
     - Waste: 1.5kg (5.7% waste)
   - System deducts from BATCH-AL-001

4. Production Completion
   - Job completed at 2:30 PM
   - Total runtime: 5 hours
   - Good pieces: 48/50 (96% yield)
   - Rejected: 2 pieces (quality issues)
```

### Phase 3: Invoicing & Dispatch
```
1. Invoice Generation
   - System creates INV-2024-089
   - Based on WO-2024-015
   - Amount: ₹15,000 + ₹2,700 GST = ₹17,700

2. Quality Check & Dispatch
   - Final inspection by Rajesh
   - Dispatch note: DN-2024-045
   - Customer pickup scheduled

3. Payment Tracking
   - Invoice status: "Sent"
   - Payment due: 15 days
   - Follow-up reminders automated
```

## 4. Identified Logical Gaps

### 4.1 Critical Missing Links

#### Gap 1: Purchase Order → Inventory Batch Creation
**Current Issue:** Manual inventory batch creation after goods receipt
**Impact:** Delayed stock updates, potential double-entry errors

#### Gap 2: Work Order → Machine Status Integration  
**Current Issue:** Machine status not automatically updated when work order starts/ends
**Impact:** Inaccurate machine utilization reporting

#### Gap 3: Material Consumption → Cost Calculation
**Current Issue:** Waste percentage not feeding into job costing
**Impact:** Inaccurate profit margins, no waste cost analysis

#### Gap 4: Multi-Vendor Material Pricing
**Current Issue:** No automatic vendor selection based on pricing/availability
**Impact:** Manual vendor selection, missed cost savings

#### Gap 5: Inventory Batch → FIFO/LIFO Logic
**Current Issue:** No automatic batch selection for material consumption
**Impact:** Potential material expiry, inaccurate costing

### 4.2 Workflow State Issues

#### Issue 1: Purchase Order Status Transitions
```
Missing States:
- "Partially Received" (when quantity differs)
- "Quality Failed" (when goods are rejected)
- "Cancelled" (vendor cancellation)
```

#### Issue 2: Work Order Dependencies
```
Missing Validations:
- Material availability check before starting
- Machine availability verification
- Operator skill-machine compatibility
```

#### Issue 3: Invoice-Payment Linkage
```
Missing Features:
- Partial payment tracking
- Payment method recording
- GST return integration
```

## 5. Recommendations & Fixes

### 5.1 Database Relationship Improvements

#### Fix 1: Enhanced Purchase Order Flow
```sql
-- Add automatic inventory batch creation
ALTER TABLE inventory_batches ADD COLUMN auto_created BOOLEAN DEFAULT FALSE;
ALTER TABLE inventory_batches ADD COLUMN grn_number VARCHAR(50);

-- Link batches to PO items directly
ALTER TABLE inventory_batches ADD COLUMN po_item_id BIGINT UNSIGNED;
ALTER TABLE inventory_batches ADD FOREIGN KEY (po_item_id) REFERENCES purchase_order_items(id);
```

#### Fix 2: Machine-Work Order Integration
```sql
-- Add machine logs table
CREATE TABLE machine_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    machine_id BIGINT UNSIGNED NOT NULL,
    work_order_id BIGINT UNSIGNED NULL,
    operator_id BIGINT UNSIGNED NOT NULL,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NULL,
    status ENUM('running', 'idle', 'maintenance', 'breakdown'),
    runtime_minutes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Fix 3: Enhanced Material Consumption
```sql
-- Add batch tracking to material consumption
ALTER TABLE material_consumptions ADD COLUMN batch_id BIGINT UNSIGNED;
ALTER TABLE material_consumptions ADD COLUMN unit_cost DECIMAL(10,2);
ALTER TABLE material_consumptions ADD COLUMN waste_cost DECIMAL(10,2);
ALTER TABLE material_consumptions ADD FOREIGN KEY (batch_id) REFERENCES inventory_batches(id);
```

### 5.2 Business Logic Enhancements

#### Enhancement 1: Smart Vendor Selection
```php
// Auto-suggest best vendor based on:
// 1. Price (lowest)
// 2. Availability (stock status)  
// 3. Payment terms (cash flow)
// 4. Past performance (delivery time, quality)

public function suggestBestVendor($materialId, $quantity) {
    return Vendor::whereHas('materials', function($q) use ($materialId) {
        $q->where('material_id', $materialId);
    })
    ->with(['materials' => function($q) use ($materialId) {
        $q->where('material_id', $materialId)
          ->orderBy('price_per_unit', 'asc');
    }])
    ->get()
    ->sortBy(function($vendor) {
        return $vendor->materials->first()->pivot->price_per_unit;
    });
}
```

#### Enhancement 2: Automated Batch Selection (FIFO)
```php
public function consumeMaterial($materialId, $quantity, $workOrderId) {
    $batches = InventoryBatch::where('material_id', $materialId)
        ->where('current_quantity', '>', 0)
        ->orderBy('received_date', 'asc') // FIFO
        ->get();
    
    $remainingQty = $quantity;
    $consumptions = [];
    
    foreach ($batches as $batch) {
        if ($remainingQty <= 0) break;
        
        $consumeQty = min($remainingQty, $batch->current_quantity);
        
        // Create consumption record
        MaterialConsumption::create([
            'work_order_id' => $workOrderId,
            'material_id' => $materialId,
            'batch_id' => $batch->id,
            'actual_quantity' => $consumeQty,
            'unit_cost' => $batch->unit_price
        ]);
        
        // Update batch quantity
        $batch->decrement('current_quantity', $consumeQty);
        
        $remainingQty -= $consumeQty;
    }
}
```

#### Enhancement 3: Real-time Cost Calculation
```php
public function calculateWorkOrderCost($workOrderId) {
    $workOrder = WorkOrder::with('materialConsumptions.material')->find($workOrderId);
    
    $materialCost = $workOrder->materialConsumptions->sum(function($consumption) {
        return $consumption->actual_quantity * $consumption->unit_cost;
    });
    
    $wasteCost = $workOrder->materialConsumptions->sum(function($consumption) {
        return $consumption->waste_quantity * $consumption->unit_cost;
    });
    
    $machineCost = $this->calculateMachineCost($workOrder);
    $laborCost = $this->calculateLaborCost($workOrder);
    
    return [
        'material_cost' => $materialCost,
        'waste_cost' => $wasteCost,
        'machine_cost' => $machineCost,
        'labor_cost' => $laborCost,
        'total_cost' => $materialCost + $wasteCost + $machineCost + $laborCost
    ];
}
```

## 6. Suggested API or Database Links

### 6.1 Missing API Endpoints

#### Procurement APIs
```php
// Auto-create inventory batches from PO
POST /api/purchase-orders/{id}/receive-goods
{
    "items": [
        {
            "po_item_id": 1,
            "received_quantity": 198,
            "quality_grade": "A",
            "batch_number": "BATCH-AL-001"
        }
    ]
}

// Get vendor suggestions for material
GET /api/materials/{id}/suggested-vendors?quantity=100
```

#### Production APIs  
```php
// Start work order with automatic machine status update
POST /api/work-orders/{id}/start
{
    "operator_id": 5,
    "machine_id": 1,
    "start_time": "2024-01-15 09:30:00"
}

// Record material consumption with batch selection
POST /api/work-orders/{id}/consume-material
{
    "material_id": 3,
    "planned_quantity": 25,
    "actual_quantity": 26.5,
    "waste_quantity": 1.5,
    "auto_select_batch": true
}
```

#### Analytics APIs
```php
// Get real-time production costs
GET /api/work-orders/{id}/cost-analysis

// Get machine utilization report
GET /api/machines/{id}/utilization?period=monthly

// Get waste analysis by material/operator
GET /api/reports/waste-analysis?group_by=material&period=weekly
```

### 6.2 Queue Jobs for Automation

```php
// Auto-create inventory batches after PO approval
CreateInventoryBatchesJob::dispatch($purchaseOrder);

// Update machine status when work order starts/ends  
UpdateMachineStatusJob::dispatch($workOrder, $status);

// Calculate and update work order costs
CalculateWorkOrderCostJob::dispatch($workOrder);

// Send low stock alerts
CheckLowStockJob::dispatch()->daily();

// Generate GST reports
GenerateGSTReportJob::dispatch()->monthly();
```

### 6.3 Integration Points

#### GST Compliance
```php
// Auto-generate GST returns
// Integration with government GST portal APIs
// Automatic tax calculation based on HSN codes
```

#### Banking Integration  
```php
// Payment gateway integration for customer payments
// Bank statement reconciliation
// Vendor payment scheduling
```

#### Barcode/QR Integration
```php
// Generate QR codes for work orders
// Barcode scanning for material consumption
// Asset tracking with QR codes
```

---

## Summary

Monitorbizz has a solid foundation for SME manufacturing management, but several critical workflow gaps need addressing:

1. **Automated inventory batch creation** from purchase orders
2. **Real-time machine status integration** with work orders  
3. **FIFO/LIFO batch consumption** logic
4. **Automated cost calculation** including waste
5. **Smart vendor selection** algorithms

Implementing these fixes will create a seamless, automated workflow that reduces manual errors and provides accurate real-time insights for better business decisions.