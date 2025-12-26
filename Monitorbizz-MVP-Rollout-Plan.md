# Monitorbizz MVP Rollout Plan
**Manufacturing SaaS for Indian SMEs - Production Deployment Strategy**

---

## 🎯 Executive Summary

Monitorbizz is ready for **phased rollout** to replace paper-based job tracking and manual invoicing in Indian SME manufacturing. This plan identifies critical logic gaps, defines a minimal viable product, and provides a safe deployment strategy.

**Current Status**: Live at https://portfolio3.lemmecode.in with core modules functional but workflow gaps present.

---

## 📊 Module Dependency Analysis

### **Independent Modules** (Can work standalone)
```
Materials ✅
├── CRUD operations complete
├── SKU/Barcode generation working
├── Business isolation enforced
└── No hard dependencies

Vendors ✅
├── Contact management complete
├── Bank details capture working
├── State/City integration functional
└── No hard dependencies

Team Management ✅
├── Role-based access working
├── Invitation system functional
├── Permission management complete
└── No hard dependencies
```

### **Dependent Modules** (Require other modules)
```
Purchase Orders ⚠️
├── DEPENDS ON: Vendors + Materials
├── CRITICAL GAP: Unit price not auto-filled from vendor-material link
├── CRITICAL GAP: No automatic inventory batch creation on approval
└── STATUS: 70% functional

Work Orders ⚠️
├── DEPENDS ON: Materials + Machines + Users
├── CRITICAL GAP: No material availability validation before start
├── CRITICAL GAP: Machine status not updated on start/complete
├── CRITICAL GAP: Waste % not used in costing calculations
└── STATUS: 60% functional

Invoices ⚠️
├── DEPENDS ON: Work Orders (optional) + Customers
├── CRITICAL GAP: No customer management module
├── CRITICAL GAP: No automatic invoice generation from completed work orders
└── STATUS: 40% functional

Inventory ⚠️
├── DEPENDS ON: Purchase Orders + Materials
├── CRITICAL GAP: Batch creation not automated
├── CRITICAL GAP: FIFO consumption not properly implemented
└── STATUS: 50% functional
```

---

## 🔄 Critical Data Flow Analysis

### **Current Workflow Gaps**

#### **1. Procurement Flow** (Vendor → PO → Inventory)
```
❌ BROKEN: Vendor-Material pricing not auto-filled in PO creation
❌ BROKEN: PO approval doesn't create inventory batches automatically
❌ BROKEN: No MOQ validation during PO creation
✅ WORKING: Vendor-material linkage exists in database
✅ WORKING: PO creation and approval workflow
```

#### **2. Production Flow** (Work Order → Material Consumption → Machine Status)
```
❌ BROKEN: Work orders start without checking material availability
❌ BROKEN: Machine status not updated when work order starts/completes
❌ BROKEN: Waste percentage not factored into material costing
✅ WORKING: Material consumption recording
✅ WORKING: Work order CRUD operations
```

#### **3. Billing Flow** (Work Order → Invoice → Payment)
```
❌ BROKEN: No customer management system
❌ BROKEN: No automatic invoice generation from completed work orders
❌ BROKEN: No material cost calculation in invoice pricing
✅ WORKING: Basic invoice CRUD
✅ WORKING: Invoice status management
```

---

## 🎯 Minimal Viable Product (MVP)

### **Core Value Proposition**
*"Replace paper job cards and manual invoicing for small metal workshops"*

### **MVP Feature Set** (5 Essential Features)

#### **1. Digital Job Cards** ⭐
- Create work orders with product name, quantity, machine assignment
- Start/complete work orders with timestamps
- Record material consumption (planned vs actual vs waste)
- **Business Value**: Eliminates paper job cards, tracks production time

#### **2. Material Management** ⭐
- Add materials with SKU, unit, basic pricing
- Track current stock levels (simple in/out)
- Material consumption tracking per work order
- **Business Value**: Know what materials are used where

#### **3. Simple Customer Invoicing** ⭐
- Create invoices with customer details
- Add line items (description, quantity, rate)
- Generate PDF invoices with GST calculation
- **Business Value**: Professional invoicing replaces handwritten bills

#### **4. Basic Machine Tracking** ⭐
- Register machines with status (available/in-use/maintenance)
- Assign work orders to specific machines
- Track machine utilization hours
- **Business Value**: Know which machine did which job

#### **5. Team Access Control** ⭐
- Role-based access (Admin, Manager, Operator, Viewer)
- Business isolation (multi-tenant)
- Activity logging for accountability
- **Business Value**: Multiple users can access without data mixing

### **MVP Exclusions** (Phase 2+)
- ❌ Vendor management
- ❌ Purchase orders
- ❌ Inventory batching
- ❌ Advanced costing
- ❌ Analytics/reporting
- ❌ Customer management module

---

## 🚀 Rollout Phases

### **Phase 1: Foundation** (Week 1-2)
**Goal**: Core modules work independently without critical bugs

#### **Must Fix Before Phase 1**:
```bash
# Critical Fixes Required
1. Work Order → Machine Status Integration
   - Update machine status to 'in_use' when work order starts
   - Update machine status to 'available' when work order completes

2. Basic Material Availability Check
   - Prevent work order start if materials not available
   - Simple stock validation (current_quantity > required_quantity)

3. Invoice Auto-numbering
   - Ensure invoice numbers are unique per business
   - Format: INV-YYYYMM-0001

4. Team Permission Enforcement
   - Operators can only see assigned work orders
   - Viewers cannot modify any data
```

#### **Phase 1 Checklist**:
- [ ] Materials CRUD working without errors
- [ ] Work Orders create/start/complete cycle functional
- [ ] Machine status updates automatically
- [ ] Basic invoicing with PDF generation
- [ ] Team roles properly enforced
- [ ] Business data isolation verified

#### **⚠️ Phase 1 Warnings**:
- **DO NOT enable Purchase Orders** - vendor-material pricing not auto-filled
- **DO NOT enable Inventory module** - batch creation not automated
- **DO NOT promise advanced costing** - waste calculations not implemented

---

### **Phase 2: Procurement** (Week 3-4)
**Goal**: Vendor → PO → Inventory flow works end-to-end

#### **Must Fix Before Phase 2**:
```bash
# Procurement Fixes Required
1. Auto-fill Unit Price in PO Creation
   - Fetch price_per_unit from material_vendor table
   - Pre-populate unit price when material is selected

2. Automatic Inventory Batch Creation
   - Create inventory batch when PO is approved
   - Link batch to purchase order for traceability

3. MOQ Validation
   - Check min_order_qty from material_vendor table
   - Prevent PO creation below minimum order quantity
```

#### **Phase 2 Checklist**:
- [ ] Vendor-material pricing auto-fills in PO
- [ ] PO approval creates inventory batches automatically
- [ ] MOQ validation prevents under-ordering
- [ ] Inventory levels update correctly
- [ ] Phase 1 features remain stable

---

### **Phase 3: Production Integration** (Week 5-6)
**Goal**: Work Orders integrate with Inventory and Costing

#### **Must Fix Before Phase 3**:
```bash
# Production Integration Fixes
1. Material Availability Validation
   - Check inventory_batches before allowing work order start
   - Show available quantity vs required quantity

2. FIFO Material Consumption
   - Deduct materials from oldest batches first
   - Update inventory_batches.current_quantity correctly

3. Waste Percentage in Costing
   - Calculate actual material cost including waste
   - Factor waste into work order costing
```

#### **Phase 3 Checklist**:
- [ ] Work orders validate material availability before start
- [ ] FIFO material consumption working
- [ ] Waste percentage affects material costing
- [ ] Inventory levels accurate after work order completion
- [ ] Phase 1 & 2 features remain stable

---

### **Phase 4: Analytics & Optimization** (Week 7-8)
**Goal**: Reporting and business intelligence

#### **Phase 4 Features**:
- Machine utilization reports
- Material consumption analysis
- Work order efficiency tracking
- Cost analysis with waste factors
- Customer profitability analysis

---

## 🔧 Critical Fixes Required

### **Immediate Fixes** (Before any production rollout)

#### **1. Work Order → Machine Status Integration**
```php
// In WorkOrderController::start()
$workOrder->machine->update(['status' => 'in_use']);

// In WorkOrderController::complete()
$workOrder->machine->update(['status' => 'available']);
```

#### **2. PO Unit Price Auto-fill**
```php
// In PurchaseOrderController::create()
// Add AJAX endpoint to fetch vendor-material pricing
public function getVendorMaterialPrice($vendorId, $materialId) {
    $materialVendor = MaterialVendor::where('vendor_id', $vendorId)
        ->where('material_id', $materialId)
        ->first();
    return response()->json([
        'unit_price' => $materialVendor->price_per_unit ?? 0,
        'min_order_qty' => $materialVendor->min_order_qty ?? 1
    ]);
}
```

#### **3. Inventory Batch Auto-creation**
```php
// In PurchaseOrderController::approve()
foreach ($purchaseOrder->items as $item) {
    InventoryBatch::create([
        'purchase_order_id' => $purchaseOrder->id,
        'material_id' => $item->material_id,
        'batch_number' => 'BATCH-' . now()->format('Ymd-His'),
        'received_quantity' => $item->quantity,
        'current_quantity' => $item->quantity,
        'unit_price' => $item->unit_price,
        'status' => 'active'
    ]);
}
```

#### **4. Material Availability Check**
```php
// In WorkOrderController::start()
private function validateMaterialAvailability($workOrder) {
    foreach ($workOrder->materialConsumptions as $consumption) {
        $available = InventoryBatch::where('material_id', $consumption->material_id)
            ->where('status', 'active')
            ->sum('current_quantity');
        
        if ($available < $consumption->planned_quantity) {
            throw new \Exception("Insufficient {$consumption->material->name}");
        }
    }
}
```

---

## ⚠️ Production Warnings

### **DO NOT Enable Until Fixed**:

1. **DO NOT enable Purchase Orders** until unit price auto-fill is implemented
2. **DO NOT enable Inventory module** until batch auto-creation works
3. **DO NOT enable advanced Work Order features** until material validation works
4. **DO NOT promise real-time costing** until waste calculations are implemented
5. **DO NOT enable multi-warehouse** until single warehouse is stable

### **Safe to Enable Immediately**:
- ✅ Materials management (CRUD only)
- ✅ Team management and permissions
- ✅ Basic work order creation (without material validation)
- ✅ Simple invoice generation
- ✅ Machine registration and basic tracking

---

## 📋 Pre-Production Checklist

### **Database Integrity**
- [ ] All foreign key constraints working
- [ ] Business isolation enforced on all tables
- [ ] No orphaned records in production data

### **Core Workflows**
- [ ] User can create material → work order → invoice (end-to-end)
- [ ] Machine status updates when work order starts/completes
- [ ] Invoice PDF generation works
- [ ] Team permissions properly restrict access

### **Performance**
- [ ] Page load times under 2 seconds
- [ ] Database queries optimized (no N+1 problems)
- [ ] File uploads working (PDF generation)

### **Security**
- [ ] Business data isolation verified
- [ ] Role-based access enforced
- [ ] No SQL injection vulnerabilities
- [ ] CSRF protection enabled

---

## 🎯 Success Metrics

### **Phase 1 Success** (Foundation)
- 5 SME workshops using digital job cards instead of paper
- 100+ work orders created and completed
- 0 critical bugs reported
- Machine status tracking working

### **Phase 2 Success** (Procurement)
- Purchase orders auto-fill pricing from vendor data
- Inventory batches created automatically on PO approval
- 0 data inconsistencies between PO and inventory

### **Phase 3 Success** (Production)
- Work orders validate material availability before start
- FIFO material consumption working correctly
- Waste percentage factored into costing

### **Overall MVP Success**
- 10+ SME manufacturers actively using the system
- Paper job cards eliminated in participating workshops
- Manual invoice writing replaced with digital invoicing
- 95%+ uptime maintained

---

## 🚨 Emergency Rollback Plan

If critical issues arise:

1. **Immediate**: Disable new user registrations
2. **Within 1 hour**: Rollback to previous stable version
3. **Within 4 hours**: Notify all active users via email
4. **Within 24 hours**: Fix critical issues and redeploy

**Rollback Triggers**:
- Data corruption or loss
- Business data mixing between tenants
- Critical workflow completely broken
- Security vulnerability discovered

---

*This plan prioritizes **working core features** over **feature completeness**. Better to have 5 features that work perfectly than 15 features with critical bugs.*

**Next Action**: Fix the 4 critical issues listed above, then begin Phase 1 rollout with selected pilot customers.