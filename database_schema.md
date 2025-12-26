# Monitorbizz Database Schema Documentation

## System Overview - Data Relationships

```
BUSINESSES (Root Entity)
├── USERS (Team members with roles)
├── MATERIALS (Raw materials, finished goods)
├── VENDORS (Suppliers with bank details)
├── MACHINES (Production equipment)
├── PURCHASE_ORDERS
│   ├── PURCHASE_ORDER_ITEMS
│   └── INVENTORY_BATCHES (Auto-created on PO receipt)
├── WORK_ORDERS (Production jobs)
│   ├── MATERIAL_CONSUMPTIONS (Materials used)
│   └── INVOICES (Customer billing)
├── MATERIAL_VENDOR (Vendor-Material pricing links)
└── NOTIFICATIONS, ACTIVITY_LOG (System tracking)
```

**Key Data Flow**: Material Selection → Vendor Discovery → Purchase Order → Inventory Batch → Work Order → Material Consumption → Invoice

---

## Core Business Tables

### businesses
**Purpose**: Root entity representing each manufacturing business (SME). Every other record belongs to a business.

**Key Columns**:
- `id` - Unique business identifier
- `name` - Business name (e.g., "Raj's Metal Workshop")
- `email`, `phone` - Primary contact details
- `subscription_plan` - Plan type (basic, premium, enterprise)
- `is_active` - Business status

**Multi-tenancy**: This IS the root tenant table. All other tables reference `business_id`.

**Relationships**: 
- Parent to ALL other tables via `business_id`
- One business → Many users, materials, vendors, etc.

---

### users
**Purpose**: Team members who access the system. Each user belongs to one business and has a specific role.

**Key Columns**:
- `id` - User identifier
- `business_id` - **CRITICAL**: Links user to their business
- `name`, `email` - User identity
- `role` - Access level (admin, manager, operator, viewer)
- `is_active` - User status
- `last_login_at` - Activity tracking

**Multi-tenancy**: ✅ `business_id` enforced. Users only see data from their business.

**Relationships**:
- `users.business_id → businesses.id`
- Users create purchase orders, work orders, invoices
- Users operate machines, consume materials

---

## Inventory & Materials

### materials
**Purpose**: Stores raw materials (steel, wood, paint), finished goods, and spare parts used by the SME.

**Key Columns**:
- `id` - Material identifier
- `business_id` - **CRITICAL**: Business ownership
- `name` - Material name (e.g., "Steel Rod 12mm")
- `sku` - Stock keeping unit code
- `unit_price` - Base price per unit
- `unit` - Measurement unit (kg, pieces, liters)
- `gst_rate` - Tax percentage
- `is_active` - Material status

**Multi-tenancy**: ✅ `business_id` enforced. Each business has its own material catalog.

**Relationships**:
- `materials.business_id → businesses.id`
- `material_vendor` - Links materials to vendors with pricing
- `purchase_order_items` - Materials ordered from vendors
- `inventory_batches` - Physical stock received
- `material_consumptions` - Materials used in production

---

### material_vendor
**Purpose**: Links materials to vendors with specific pricing and minimum order quantities. Enables intelligent procurement.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `material_id` - Which material
- `vendor_id` - Which vendor supplies it
- `price_per_unit` - Vendor-specific price
- `min_order_qty` - Minimum order quantity
- `notes` - Payment terms, delivery notes

**Multi-tenancy**: ✅ `business_id` enforced. Unique constraint: `(vendor_id, material_id, business_id)`

**Relationships**:
- `material_vendor.business_id → businesses.id`
- `material_vendor.material_id → materials.id`
- `material_vendor.vendor_id → vendors.id`

**Business Impact**: "Steel Rod available from Vendor A at ₹45/kg (MOQ: 100kg) vs Vendor B at ₹42/kg (MOQ: 500kg)"

---

### inventory_batches
**Purpose**: Tracks physical stock received from purchase orders. Each batch has quantity, location, and expiry tracking.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `batch_number` - Unique batch identifier
- `purchase_order_id` - Source PO that created this batch
- `material_id` - What material this batch contains
- `received_quantity` - Quantity received
- `current_quantity` - Current available quantity
- `storage_location` - Where it's stored
- `status` - Batch status (received, consumed, expired)

**Multi-tenancy**: ✅ `business_id` enforced.

**Relationships**:
- `inventory_batches.business_id → businesses.id`
- `inventory_batches.purchase_order_id → purchase_orders.id`
- `inventory_batches.material_id → materials.id`

**Business Impact**: "Batch #2025-001 has 50kg Steel Rod from PO-2025-001, stored in Rack A-1"

---

## Procurement System

### vendors
**Purpose**: Supplier companies that provide materials. Includes contact details, addresses, and bank information for payments.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `name` - Vendor name (e.g., "Steel Suppliers Ltd")
- `email`, `phone` - Contact details
- `company_address` - Business address
- `bank_name`, `account_number`, `ifsc_code` - Payment details

**Multi-tenancy**: ✅ `business_id` enforced. Each business manages its own vendor network.

**Relationships**:
- `vendors.business_id → businesses.id`
- `material_vendor` - Links vendors to materials with pricing
- `purchase_orders` - Orders placed with vendors

---

### purchase_orders
**Purpose**: Orders placed with vendors to buy materials. Tracks approval workflow and delivery status.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `po_number` - Human-readable PO number (PO-2025-001)
- `vendor_id` - Which vendor this order is placed with
- `status` - Order status (pending, approved, received, completed)
- `total_amount` - Total order value
- `po_date` - Order date

**Multi-tenancy**: ✅ `business_id` enforced.

**Relationships**:
- `purchase_orders.business_id → businesses.id`
- `purchase_orders.vendor_id → vendors.id`
- `purchase_order_items` - Individual materials in this order
- `inventory_batches` - Stock created when PO is received

**Business Flow**: PO Created → Admin Approves → Vendor Delivers → Inventory Batches Created

---

### purchase_order_items
**Purpose**: Individual line items within a purchase order. Each item represents one material with quantity and pricing.

**Key Columns**:
- `purchase_order_id` - Parent purchase order
- `material_id` - Which material is being ordered
- `quantity` - How much to order
- `unit_price` - Price per unit for this order
- `gst_rate` - Tax rate applied
- `total_price` - Line total (quantity × unit_price + GST)

**Multi-tenancy**: Inherited through `purchase_orders.business_id`

**Relationships**:
- `purchase_order_items.purchase_order_id → purchase_orders.id`
- `purchase_order_items.material_id → materials.id`

---

## Production System

### machines
**Purpose**: Production equipment like CNC machines, lathes, welding setups, injection molders.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `name` - Machine name (e.g., "CNC Machine #1")
- `code` - Machine code (CNC-01)
- `type` - Machine category
- `status` - Current status (active, maintenance, broken)
- `location` - Physical location in workshop

**Multi-tenancy**: ✅ `business_id` enforced.

**Relationships**:
- `machines.business_id → businesses.id`
- `work_orders` - Jobs assigned to this machine

---

### work_orders
**Purpose**: Production jobs assigned to machines. Digital replacement for paper job cards.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `wo_number` - Work order number (WO-2025-001)
- `machine_id` - Which machine will do this job
- `operator_id` - Which user is assigned to operate
- `product_name` - What is being produced
- `quantity` - How many pieces to produce
- `status` - Job status (pending, in_progress, completed)
- `assigned_to` - User assigned to this job

**Multi-tenancy**: ✅ `business_id` enforced.

**Relationships**:
- `work_orders.business_id → businesses.id`
- `work_orders.machine_id → machines.id`
- `work_orders.operator_id → users.id`
- `material_consumptions` - Materials used in this job
- `invoices` - Customer billing for this job

**Business Impact**: "Job #2025-001 ran on CNC-02 from 9:15 AM to 2:30 PM, operated by Ravi"

---

### material_consumptions
**Purpose**: Tracks which materials were used in which work orders. Enables cost calculation and waste tracking.

**Key Columns**:
- `work_order_id` - Which job used the materials
- `material_id` - Which material was consumed
- `quantity_used` - How much was consumed
- `batch_id` - Which inventory batch it came from
- `waste_quantity` - How much was wasted

**Multi-tenancy**: Inherited through `work_orders.business_id`

**Relationships**:
- `material_consumptions.work_order_id → work_orders.id`
- `material_consumptions.material_id → materials.id`
- `material_consumptions.batch_id → inventory_batches.id`

**Business Impact**: "This job used 2.3kg Mild Steel + 1.5L Paint, 5% scrap on Batch #X"

---

## Customer & Billing

### invoices
**Purpose**: Customer billing for completed work orders. Tracks payments and outstanding amounts.

**Key Columns**:
- `business_id` - **CRITICAL**: Business ownership
- `invoice_number` - Invoice number (INV-2025-001)
- `work_order_id` - Which job this invoice is for
- `customer_name`, `customer_email` - Customer details
- `subtotal`, `tax_amount`, `total_amount` - Pricing breakdown
- `status` - Payment status (draft, sent, paid, overdue)
- `due_date`, `paid_date` - Payment tracking

**Multi-tenancy**: ✅ `business_id` enforced.

**Relationships**:
- `invoices.business_id → businesses.id`
- `invoices.work_order_id → work_orders.id`

---

### invoice_items
**Purpose**: Individual line items within an invoice. Breaks down charges for materials, labor, etc.

**Key Columns**:
- `invoice_id` - Parent invoice
- `description` - What is being charged for
- `quantity` - How many units
- `unit_price` - Price per unit
- `total_price` - Line total

**Multi-tenancy**: Inherited through `invoices.business_id`

**Relationships**:
- `invoice_items.invoice_id → invoices.id`

---

## System Tables

### notifications
**Purpose**: System notifications for users (PO approvals, low stock alerts, job completions).

**Key Columns**:
- `id` - Notification ID
- `type` - Notification type
- `notifiable_type`, `notifiable_id` - Who gets notified (usually User)
- `data` - Notification content (JSON)
- `read_at` - When notification was read

**Multi-tenancy**: Notifications are sent to users, who belong to businesses.

---

### invitations
**Purpose**: Pending invitations for new team members to join a business.

**Key Columns**:
- `business_id` - **CRITICAL**: Which business is inviting
- `email` - Invited user's email
- `role` - Role they'll have when they join
- `status` - Invitation status (pending, accepted, expired)

**Multi-tenancy**: ✅ `business_id` enforced.

---

### states, cities
**Purpose**: Geographic data for address validation in vendor and user forms.

**Key Columns**:
- `states.name` - State name
- `cities.name` - City name
- `cities.state_id` - Which state this city belongs to

**Multi-tenancy**: Shared reference data, not business-specific.

---

## Critical Business Flows

### 1. Procurement Flow
```
Material Selection → Vendor Discovery (material_vendor) → Purchase Order Creation → 
Admin Approval → Vendor Delivery → Inventory Batch Creation → Stock Available
```

### 2. Production Flow
```
Work Order Creation → Machine Assignment → Material Consumption → 
Job Completion → Invoice Generation → Customer Payment
```

### 3. Multi-Tenancy Enforcement
- Every business-specific table has `business_id`
- All queries filtered by authenticated user's `business_id`
- Unique constraints include `business_id` where needed
- Users can only access data from their own business

### 4. Intelligent Procurement
- `material_vendor` table enables price comparison
- System shows: "Steel Rod available from 3 vendors: ₹45/kg, ₹42/kg, ₹48/kg"
- MOQ validation prevents ordering below minimum quantities
- Auto-fill prices when vendor is selected

This schema transforms small manufacturing from paper-based chaos into organized, data-driven operations while maintaining strict business isolation for multi-tenant security.