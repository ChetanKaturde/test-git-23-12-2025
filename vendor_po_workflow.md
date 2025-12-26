# Vendor-Material Linkage & Intelligent Purchase Order Workflow

## Why This Feature Was Needed

Small manufacturing businesses (SMEs) face critical procurement challenges:

- **Manual Price Tracking**: Shop owners memorize vendor prices, leading to errors and missed savings
- **MOQ Confusion**: Minimum order quantities are forgotten, causing order rejections
- **Vendor Selection**: No systematic way to compare vendors for the same material
- **Inventory Gaps**: Purchase orders don't automatically update stock levels
- **Paper-Based Workflow**: Procurement decisions made on gut feeling rather than data

**Real SME Use Case**: 
*"Raj's Metal Workshop orders steel from 3 different vendors. Vendor A has better prices for small quantities, Vendor B for bulk orders, and Vendor C for urgent deliveries. Without Monitorbizz, Raj had to remember these details or dig through old invoices every time he needed to place an order."*

## What Was Implemented

### 1. Enhanced Database Schema
- **material_vendor table** with business_id scoping:
  - `vendor_id`, `material_id`, `business_id` (unique constraint)
  - `price_per_unit` - vendor-specific pricing
  - `min_order_qty` - minimum order quantity per vendor
  - `notes` - payment terms, delivery notes, etc.

### 2. API Endpoints
- `POST /api/vendors/{vendor}/link-materials` - Link materials to vendors with pricing/MOQ
- `GET /api/materials/{material}/vendors` - Get all vendors for a material
- `GET /api/materials/{material}/vendors-for-po` - Get vendors for PO creation

### 3. UI Enhancements
- **Material Edit Page**: Vendor linkage section with pricing and MOQ management
- **PO Creation**: Auto-fetch eligible vendors when material is selected
- **Price Auto-fill**: Vendor prices populate automatically
- **MOQ Validation**: Quantity validation against minimum order requirements

### 4. Business Logic
- **Multi-tenancy**: All data scoped to `business_id`
- **Inventory Integration**: PO receipt creates inventory batches automatically
- **Payment Terms**: Dropdown with common SME terms (50% Advance, 30 Days Credit, etc.)

## How It Works - User Flow

### Step 1: Material → Vendor Linkage
1. Navigate to Materials → Edit Material
2. In "Vendor Pricing & MOQ" section, click "Add Vendor"
3. Select vendor, enter price per unit, minimum order quantity, and notes
4. Save linkage - now this material is connected to vendor with pricing

### Step 2: Intelligent PO Creation
1. Navigate to Purchase Orders → Create New
2. Select a material from dropdown
3. **System automatically**:
   - Fetches all vendors who supply this material
   - Shows vendor names, prices, and MOQ requirements
   - Pre-fills price when vendor is selected
4. Validate quantity meets MOQ requirements
5. Add payment terms (50% Advance, 30 Days Credit, etc.)

### Step 3: PO → Inventory Flow
1. Submit PO (status: pending)
2. Admin approves PO
3. **System automatically**:
   - Creates inventory batches for each material
   - Updates stock levels
   - Links batches to original PO for traceability

### Step 4: Real-World Benefits
- **Price Comparison**: "Steel Rod available from Vendor A at ₹45/kg (MOQ: 100kg) vs Vendor B at ₹42/kg (MOQ: 500kg)"
- **Smart Ordering**: System prevents ordering 50kg from Vendor B (below 500kg MOQ)
- **Inventory Tracking**: Every material batch traces back to its PO and vendor
- **Payment Planning**: "This PO requires 50% advance payment (₹25,000) before delivery"

## Technical Implementation

### Models Enhanced
- `MaterialVendor` - Pivot model with business scoping
- `PurchaseOrder` - Enhanced with payment terms
- `InventoryBatch` - Auto-creation on PO receipt

### Controllers Enhanced
- `VendorController::linkMaterials()` - Material-vendor linkage
- `VendorController::getVendorsForMaterial()` - Vendor lookup for materials
- `PurchaseOrderController::getVendorsForMaterial()` - PO-specific vendor fetching

### Business Rules
- Unique constraint: `(vendor_id, material_id, business_id)`
- MOQ validation during PO creation
- Automatic inventory batch creation on PO approval
- Multi-tenant data isolation

## SME Impact

**Before**: "I think Vendor A was cheaper for steel... let me call and check prices again"

**After**: "System shows Vendor A: ₹45/kg (MOQ: 100kg), Vendor B: ₹42/kg (MOQ: 500kg). For my 200kg order, Vendor A is better. Price auto-filled, MOQ validated, PO created in 2 minutes."

This feature transforms procurement from memory-based guesswork into data-driven decision making, exactly what small manufacturers need to compete effectively.