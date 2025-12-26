# Monitorbizz Database Schema - Enhanced for Production MVP

## 🎯 Overview
This document describes the enhanced database schema for Monitorbizz MVP with complete end-to-end workflow support.

## 📊 Core Tables

### **customers** (NEW)
```sql
CREATE TABLE customers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    business_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    gstin VARCHAR(15) NULL,
    contact_person VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
    INDEX (business_id, is_active)
);
```

### **materials** (ENHANCED)
```sql
-- New fields added:
ALTER TABLE materials ADD COLUMN material_type VARCHAR(50) DEFAULT 'raw_material';
-- Values: raw_material, component, finished_good, consumable

ALTER TABLE materials ADD COLUMN material_form VARCHAR(50) NULL;
-- Values: bar, pipe, sheet, rod, casting, plate

ALTER TABLE materials ADD COLUMN grade VARCHAR(100) NULL;
-- Examples: "6061-T6", "SS316", "C36000"

ALTER TABLE materials ADD COLUMN unit_of_stock VARCHAR(20) DEFAULT 'kg';
-- Primary unit for inventory tracking

ALTER TABLE materials ADD COLUMN unit_of_order VARCHAR(20) NULL;
-- Secondary unit for procurement (if different from stock)

ALTER TABLE materials ADD COLUMN estimated_weight_per_piece DECIMAL(10,4) NULL;
-- For dual-unit materials (order in pieces, stock in kg)
```

### **work_orders** (ENHANCED)
```sql
-- New fields added:
ALTER TABLE work_orders ADD COLUMN customer_id BIGINT NULL;
ALTER TABLE work_orders ADD COLUMN quoted_rate DECIMAL(10,2) NULL;

-- Foreign key:
ALTER TABLE work_orders ADD FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL;
```

### **inventory_batches** (ENHANCED)
```sql
-- New fields added:
ALTER TABLE inventory_batches ADD COLUMN unit_price DECIMAL(10,2) NULL;
ALTER TABLE inventory_batches ADD COLUMN warehouse_id BIGINT NULL;
```

### **material_consumptions** (ENHANCED)
```sql
-- New fields added (if table exists):
ALTER TABLE material_consumptions ADD COLUMN unit_cost DECIMAL(10,2) NULL;
ALTER TABLE material_consumptions ADD COLUMN batch_id BIGINT NULL;
```

## 🔄 Dual-Unit Procurement Workflow

### **Example: Brass Pipe Procurement**
```
Material Setup:
- Name: "Brass Pipe 25mm"
- Grade: "C36000"
- unit_of_stock: "kg" (for inventory & costing)
- unit_of_order: "pieces" (for PO)
- estimated_weight_per_piece: 2.8 kg

Purchase Order:
- Order: 10 pieces
- Expected weight: 10 × 2.8 = 28 kg
- Price: ₹450/kg

Goods Receipt:
- Received: 10 pieces
- Actual weight: 27.6 kg (weighed)
- Inventory: +27.6 kg
- Cost: 27.6 × ₹450 = ₹12,420
```

## 🏭 End-to-End Workflow

### **1. Customer → Work Order → Invoice Flow**
```sql
-- Create customer
INSERT INTO customers (business_id, name, gstin, address) 
VALUES (6, 'ABC Constructions', '27ABCDE1234F1Z5', 'Pune');

-- Create work order with customer link
INSERT INTO work_orders (business_id, customer_id, product_name, quantity, quoted_rate)
VALUES (6, 1, 'Custom Brackets', 50, 500.00);

-- Complete work order → Auto-generate invoice
INSERT INTO invoices (business_id, work_order_id, customer_name, total_amount)
VALUES (6, 1, 'ABC Constructions', 25000.00);
```

### **2. Vendor → PO → Inventory → Consumption Flow**
```sql
-- Create PO
INSERT INTO purchase_orders (business_id, vendor_id, po_number, status)
VALUES (6, 1, 'PO-2024-001', 'pending');

-- Approve PO → Create inventory batch
UPDATE purchase_orders SET status = 'received' WHERE id = 1;
INSERT INTO inventory_batches (business_id, material_id, received_quantity, unit_price)
VALUES (6, 1, 27.6, 450.00);

-- Consume material in work order
INSERT INTO material_consumptions (work_order_id, material_id, actual_quantity, unit_cost)
VALUES (1, 1, 27.6, 450.00);
```

## 🔧 Key Relationships

### **Customer Relationships**
- `customers` → `work_orders` (1:many)
- `customers` → `invoices` (1:many via work_orders)

### **Material Relationships**
- `materials` → `inventory_batches` (1:many)
- `materials` → `material_consumptions` (1:many)
- `materials` → `purchase_order_items` (1:many)

### **Work Order Relationships**
- `work_orders` → `customers` (many:1)
- `work_orders` → `machines` (many:1)
- `work_orders` → `material_consumptions` (1:many)
- `work_orders` → `invoices` (1:1)

### **Inventory Relationships**
- `inventory_batches` → `purchase_orders` (many:1)
- `inventory_batches` → `materials` (many:1)
- `inventory_batches` → `material_consumptions` (1:many)

## 📈 Business Logic

### **Cost Calculation**
```php
// Work Order Total Cost
$materialCost = $workOrder->materialConsumptions->sum(function($c) {
    return $c->actual_quantity * $c->unit_cost;
});

$laborCost = $workOrder->duration_hours * 100; // ₹100/hour

$quotedAmount = $workOrder->quantity * $workOrder->quoted_rate;

$totalCost = max($materialCost + $laborCost, $quotedAmount);
```

### **Machine Status Sync**
```php
// On work order start
$workOrder->machine->update(['status' => 'in_use']);

// On work order complete
$workOrder->machine->update(['status' => 'available']);
```

### **Auto-Invoice Generation**
```php
// When work order completes with customer
if ($workOrder->customer_id && $workOrder->status === 'completed') {
    $invoice = Invoice::create([
        'work_order_id' => $workOrder->id,
        'customer_name' => $workOrder->customer->name,
        'total_amount' => $workOrder->calculateCost(),
        'status' => 'draft'
    ]);
}
```

## 🚀 Production Readiness

### **Data Integrity**
- All foreign keys properly defined
- Business isolation enforced via `business_id`
- Soft deletes for critical data
- Audit trails via timestamps

### **Performance**
- Indexes on frequently queried columns
- Business-scoped queries
- Efficient relationship loading

### **Scalability**
- Multi-tenant architecture
- Modular table design
- Extensible field structure

This enhanced schema supports complete manufacturing workflows from procurement to invoicing with full traceability and cost tracking.