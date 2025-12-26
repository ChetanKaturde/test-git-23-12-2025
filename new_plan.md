# Monitorbizz — MVP Development Plan (v1.0)

## 🚨 Critical Fixes (Do First)

- [ ] **Bug: Permission System Broken** → File: `resources/views/layouts/app.blade.php` + `app/Http/Controllers/DashboardController.php` → Fix: Replace hard-coded role checks with `can('view_inventory')`, `can('create_po')` throughout sidebar and controllers
- [ ] **Bug: Duplicate Routes Causing 404s** → File: `routes/web.php` lines 400-600 → Fix: Remove all duplicate route definitions, test every navigation link
- [ ] **Bug: PO Approval Doesn't Create Inventory** → File: `app/Http/Controllers/PurchaseOrderController.php` → Fix: Add event listener on PO approval to auto-create InventoryBatch with quantity, vendor, date
- [ ] **Bug: Material Stock Validation Missing** → File: `app/Http/Controllers/PurchaseOrderController.php` lines 200-250 → Fix: Check available quantity before PO creation, show error "Only 15kg available. Reduce quantity?"
- [ ] **Bug: N+1 Query in Purchase Orders** → File: `app/Http/Controllers/PurchaseOrderController.php` line 25 → Fix: Add `with(['vendor', 'items.material'])` to index query

## ✨ Must-Have MVP Features

- [ ] **Feature: Multi-tenancy** → Why: Businesses must not see each other's data → How: Add `business_id` to all models, add global scope to all queries, create Business model <!-- UI: Business selector dropdown in top nav for admin users. Regular users see only their business name. -->
- [ ] **Feature: Machine & Work Order Tracking** → Why: Core workflow for workshops → How: Create Machine model (name, type, status), WorkOrder model (machine_id, item_id, hours), link material consumption <!-- UI: Simple "Add Machine" button on dashboard. Work Order form with machine dropdown, material selector, and "Start Job" button. -->
- [ ] **Feature: Legal Invoice System** → Why: Must replace paper invoices with tax compliance → How: Create Invoice model with auto-numbering (INV-2025-001), tax calculation (5%/12%/18% GST), PDF generation via DomPDF <!-- UI: One "Generate Invoice" button on Work Order view. Shows preview before download. No fields to fill — auto-populated from work order. -->
- [ ] **Feature: Onboarding Wizard** → Why: New users don't know where to start → How: 3-step modal on first login: "Create Item" → "Add Machine" → "Create Work Order" <!-- UI: Full-screen modal with progress dots. Each step has one big button and simple explanation. Skip option in corner. -->
- [ ] **Feature: Form Request Validation** → Why: Inconsistent validation causes errors → How: Create `app/Http/Requests/StorePurchaseOrderRequest.php`, `app/Http/Requests/StoreWorkOrderRequest.php`, `app/Http/Requests/StoreInvoiceRequest.php` <!-- UI: Field-level error messages in red below inputs. Success messages as green toast notifications. -->

## 🚫 Ignore These (For Now)

- [ ] **Python Scripts (ML, Analytics)** → Why: MVP needs basic functionality first, not AI predictions
- [ ] **React/Vue Frontend** → Why: Blade templates work fine, don't over-engineer
- [ ] **Payment Gateway Integration** → Why: Workshop owners will record "cash received" manually
- [ ] **Advanced Analytics Dashboard** → Why: Show simple stats: "120kg used, 8kg wasted, 3 invoices sent"
- [ ] **Mobile App** → Why: Responsive web is sufficient for MVP
- [ ] **Multi-currency Support** → Why: Focus on local currency (INR) only
- [ ] **Excel/CSV Export** → Why: PDF invoices are enough to replace paper
- [ ] **Third-party Integrations** → Why: WhatsApp/Telegram can wait until after MVP

## 🎯 Success Definition

> "MVP is ready when a workshop owner can create an item, log a work order, track material used, and generate a legal invoice with tax — all in under 5 minutes, without training."

## 📅 Timeline

| Week | Focus | Deliverable |
|------|-------|-------------|
| 1 | Fix Critical Bugs | Permission system works, no 404s, PO→Inventory flow |
| 2 | Add Multi-tenancy | Business separation, data isolation |
| 3 | Build Machine/WorkOrder | Core workflow tracking |
| 4 | Legal Invoice System | Tax-compliant PDF invoices |
| 5 | Polish & Test | Onboarding wizard, mobile-friendly UI |

## 🔧 Implementation Priority

### Week 1: Critical Fixes
1. Fix `resources/views/layouts/app.blade.php` sidebar permission checks
2. Clean up `routes/web.php` duplicates
3. Add PO approval → inventory creation
4. Fix material stock validation
5. Optimize PO index query

### Week 2: Multi-tenancy
1. Create Business model and migration
2. Add `business_id` to all existing models
3. Add global scope to all queries
4. Update seeders and factories

### Week 3: Core Workflow
1. Create Machine model and CRUD
2. Create WorkOrder model and CRUD
3. Link WorkOrders to Machines and Materials
4. Add material consumption tracking

### Week 4: Legal Invoices
1. Create Invoice model with auto-numbering
2. Add tax calculation (GST rates)
3. Build PDF generation with DomPDF
4. Create invoice CRUD interface

### Week 5: Polish
1. Add onboarding wizard modal
2. Improve mobile responsiveness
3. Add tooltips and breadcrumbs
4. Test complete user journey

## 🎯 MVP Success Metrics

- [ ] New user completes setup in <5 minutes
- [ ] Can create item, machine, work order without errors
- [ ] Material consumption auto-deducts from inventory
- [ ] Invoice generates with correct tax calculation
- [ ] PDF invoice prints cleanly
- [ ] No 404 errors in navigation
- [ ] Non-admin users see only permitted features

## 🚀 Post-MVP (Later)

- Advanced analytics and reporting
- Python integration for insights
- Payment gateway integration
- Mobile app development
- Multi-currency support
- Third-party integrations
- Advanced inventory features