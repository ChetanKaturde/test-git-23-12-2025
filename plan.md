# Monitorbizz — Development & Fix Plan

## 🚨 Critical Issues (Fix First)

- [ ] **Bug: Inconsistent Permission System** → Impact: Users can't access features they should have → Files: `User.php`, `Permission.php`, `Module.php`, `DashboardController.php` → Fix: Standardize permission checking across controllers and views
- [ ] **Bug: Broken Module-Permission Relationship** → Impact: Sidebar navigation fails for non-admin users → Files: `app.blade.php` lines 150-200, `DashboardController.php` → Fix: Implement proper module-permission joins
- [ ] **UX Flow Break: Purchase Order → Inventory Disconnect** → Impact: Created POs don't automatically create inventory batches → Files: `PurchaseOrderController.php`, `InventoryController.php` → Fix: Add event listener to auto-create inventory on PO approval
- [ ] **Bug: Duplicate Route Definitions** → Impact: Route conflicts causing 404s → Files: `web.php` lines 400-600 → Fix: Remove duplicate routes and consolidate similar ones
- [ ] **Bug: Material Quantity Validation Logic** → Impact: Users can order more than available stock → Files: `PurchaseOrderController.php` lines 200-250 → Fix: Implement proper stock checking before PO creation
- [ ] **UX Flow Break: No "Getting Started" for New Users** → Impact: New workshop owners don't know where to start → Files: Dashboard views → Fix: Add onboarding wizard for first-time users

## 🛠️ Technical Debt (Refactor)

- [ ] **Duplicated Logic: Dashboard Stats Generation** → Extract to `DashboardStatsService` class → Files: `DashboardController.php` lines 100-300
- [ ] **Missing Policies: Material/Vendor CRUD** → Add ability to restrict "Delete Material" to Owner only → Files: Create `MaterialPolicy.php`, `VendorPolicy.php`
- [ ] **N+1 Query: Purchase Orders Index** → Use `with(['vendor', 'items.material'])` → Files: `PurchaseOrderController.php` line 25
- [ ] **Inconsistent Validation Rules** → Create Form Request classes → Files: All controllers using inline validation
- [ ] **Hard-coded Role Checks** → Replace with permission-based checks → Files: `app.blade.php`, all controllers
- [ ] **Missing Database Indexes** → Add indexes on frequently queried columns → Files: All migration files
- [ ] **Inconsistent Error Handling** → Standardize try-catch blocks and error responses → Files: All controllers

## ✨ Feature Gaps (Modular Flow)

- [ ] **"Machines" Module Missing** → Need Machine model, controller, views → Create machine registration and usage logging
- [ ] **"Work Orders" Not Connected to Machines** → Need pivot table `machine_work_orders` → Add form to assign work orders to specific machines
- [ ] **"Material Consumption" Not Auto-calculated** → Add event listener on Work Order completion → Track actual vs planned material usage
- [ ] **No "Waste Tracking" UI** → Add modal/form under Work Order view → Record scrap percentage and reasons
- [ ] **Missing "Batch Tracking"** → Connect materials to finished products → Add batch numbers to track quality issues
- [ ] **No "Maintenance Scheduler"** → Add machine maintenance reminders → Create maintenance_schedules table
- [ ] **Missing "Yield Calculator"** → Auto-calculate production efficiency → Add yield percentage to work orders

## 🐍 Python Integration Opportunities

- [ ] **Machine Learning: Predict Machine Failures** → Use Python script to analyze `machine_usage_logs.csv` → Detect usage patterns → Send alerts via Laravel Queue → Files: Create `scripts/predict_maintenance.py`
- [ ] **Data Analysis: Material Waste Patterns** → Python script to analyze waste data → Generate insights on cost optimization → Trigger via Artisan command → Files: Create `scripts/analyze_waste.py`
- [ ] **Professional PDF Generation** → Use ReportLab for better invoice/work order templates → Replace DomPDF for complex layouts → Files: Create `scripts/generate_pdf.py`
- [ ] **Barcode/QR Generation Enhancement** → Use Python libraries for better barcode quality → Batch generation for inventory items → Files: Create `scripts/generate_barcodes.py`
- [ ] **CSV/Excel Import for Legacy Data** → Python script to clean and import old workshop data → Handle data validation and transformation → Files: Create `scripts/import_legacy_data.py`

## 🧩 Modular Architecture Improvements

- [ ] **Refactor Feature Activation System** → Move from boolean flags in users table → Use `business_features` table with `business_id`, `feature_name`, `enabled`, `activated_at`
- [ ] **Build FeatureManager Service** → Check if feature is active before rendering UI → Centralize feature access control → Files: Create `app/Services/FeatureManager.php`
- [ ] **Modularize Route Groups** → Each module (Items, Machines, WorkOrders) gets own route file → Self-contained with middleware and policies → Files: Create `routes/modules/` directory
- [ ] **Implement Business Multi-tenancy** → Add `business_id` to all models → Scope queries by business → Files: Add migration, update all models
- [ ] **Create Module Installer** → One-click feature activation → Auto-create necessary database tables → Files: Create `app/Services/ModuleInstaller.php`

## 📱 UI/UX Improvements (Non-Code)

- [ ] **Add Contextual Tooltips** → "What's a Work Order?" for first-time users → Use Bootstrap tooltips with simple explanations
- [ ] **Simplify Dashboard Layout** → Show only active modules → Hide unused features to reduce cognitive load
- [ ] **Mobile-First Button Sizing** → Ensure all buttons are tappable on small screens → Minimum 44px touch targets
- [ ] **Add "Getting Started" Checklist** → Guide new businesses through setup → "Create your first item → Add a vendor → Create work order"
- [ ] **Improve Form Validation Feedback** → Clear success/error messages → Show field-level validation errors
- [ ] **Add Breadcrumb Navigation** → Help users understand where they are → Especially important in deep module navigation

## 📅 Timeline & Priority

| Phase | Goal | Estimated Time |
|-------|------|----------------|
| 1. Fix | Critical bugs & UX breaks | 1 week |
| 2. Refactor | Code cleanup + modular structure | 2 weeks |
| 3. Enhance | Add Machines + Material Consumption flow | 2 weeks |
| 4. Integrate | Python scripts for analytics & PDFs | 1–2 weeks |
| 5. Polish | UI/UX polish + onboarding | 1 week |

## ✅ Success Criteria

- All critical bugs fixed
- New users can complete "Create Item → Create Work Order → Log Machine Use → Track Material Used" in <3 minutes
- Admin can enable/disable features per business without touching code
- Python scripts run cleanly via Laravel queues or Artisan commands
- README.md and plan.md are aligned and clear to non-technical team members

## 🔧 Specific File Fixes Needed

### High Priority
- `routes/web.php` → Remove duplicate routes (lines 400-600)
- `app/Http/Controllers/DashboardController.php` → Fix permission checking logic
- `resources/views/layouts/app.blade.php` → Fix sidebar navigation for non-admin users
- `app/Models/User.php` → Standardize permission methods
- `app/Http/Controllers/PurchaseOrderController.php` → Fix material quantity validation

### Medium Priority
- Create `app/Services/DashboardStatsService.php`
- Create `app/Services/FeatureManager.php`
- Create `app/Policies/MaterialPolicy.php`
- Create `app/Http/Requests/StorePurchaseOrderRequest.php`
- Add database indexes to frequently queried tables

### Low Priority
- Create `scripts/` directory for Python integration
- Create `routes/modules/` for modular routing
- Add `business_id` column to all relevant tables
- Create onboarding wizard views

## 🎯 Workshop Owner Experience Goals

After fixes, a workshop owner should be able to:
1. Sign up and immediately understand what to do first
2. Create materials (steel, paint, screws) in under 2 minutes
3. Add a machine (CNC, lathe) and start logging jobs
4. Track material consumption per job without manual calculation
5. See waste patterns and cost insights automatically
6. Generate professional invoices and work orders
7. Add team members with appropriate permissions
8. Enable/disable features as their business grows

This plan transforms Monitorbizz from a complex inventory system into a simple, modular workshop management tool that grows with small manufacturers.