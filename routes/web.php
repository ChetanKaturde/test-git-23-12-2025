<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Contact form
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/test', function () {
    return 'Laravel is working! Time: ' . now();
});

Route::get('/test-state-city', function () {
    return view('test-state-city');
});

Route::get('/test-materials-create', function () {
    return 'Materials create route test - ' . now();
});

// Test materials create without auth
Route::get('/materials/create-test', [MaterialController::class, 'create']);

// Direct view test
Route::get('/materials/create-direct', function() {
    return view('materials.create');
});

// Simple test without layout
Route::get('/materials/create-simple', function() {
    return '<h1>Materials Create Form</h1><p>This should work if routing is OK</p>';
});

// Debug route
Route::get('/debug-session', function() {
    return [
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->only(['id', 'email', 'role', 'business_id']) : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token(),
        'session_data' => session()->all()
    ];
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/realtime', [DashboardController::class, 'getRealtimeData'])->name('dashboard.realtime');
    
    // Materials - temporarily without middleware for testing
    Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('materials/create', function() {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('materials.create');
    })->name('materials.create');
    Route::post('materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    Route::middleware('module.permission:materials,edit')->group(function () {
        Route::get('materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
        Route::put('materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
        Route::patch('materials/{material}', [MaterialController::class, 'update']);
    });
    Route::middleware('module.permission:materials,delete')->group(function () {
        Route::delete('materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    });
    
    // Vendors - with module permission
    Route::middleware('module.permission:vendors,view')->group(function () {
        Route::get('vendors', [VendorController::class, 'index'])->name('vendors.index');
    });
    Route::middleware('module.permission:vendors,create')->group(function () {
        Route::get('vendors/create', [VendorController::class, 'create'])->name('vendors.create');
        Route::post('vendors', [VendorController::class, 'store'])->name('vendors.store');
    });
    Route::middleware('module.permission:vendors,view')->group(function () {
        Route::get('vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    });
    Route::middleware('module.permission:vendors,edit')->group(function () {
        Route::get('vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
        Route::put('vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
        Route::patch('vendors/{vendor}', [VendorController::class, 'update']);
    });
    Route::middleware('module.permission:vendors,delete')->group(function () {
        Route::delete('vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');
    });
    
    // Purchase Orders - with module permission
    Route::middleware('module.permission:purchase_orders,view')->group(function () {
        Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    });
    Route::middleware('module.permission:purchase_orders,create')->group(function () {
        Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
        Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    });
    Route::middleware('module.permission:purchase_orders,view')->group(function () {
        Route::get('purchase-orders/{id}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    });
    Route::middleware('module.permission:purchase_orders,edit')->group(function () {
        Route::get('purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
        Route::put('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
        Route::patch('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update']);
        Route::post('purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    });
    Route::middleware('module.permission:purchase_orders,delete')->group(function () {
        Route::delete('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    });
    
    // Machines - with module permission
    Route::middleware('module.permission:machines,view')->group(function () {
        Route::get('machines', [MachineController::class, 'index'])->name('machines.index');
    });
    Route::middleware('module.permission:machines,create')->group(function () {
        Route::get('machines/create', [MachineController::class, 'create'])->name('machines.create');
        Route::post('machines', [MachineController::class, 'store'])->name('machines.store');
    });
    Route::middleware('module.permission:machines,view')->group(function () {
        Route::get('machines/{machine}', [MachineController::class, 'show'])->name('machines.show');
    });
    Route::middleware('module.permission:machines,edit')->group(function () {
        Route::get('machines/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
        Route::put('machines/{machine}', [MachineController::class, 'update'])->name('machines.update');
        Route::patch('machines/{machine}', [MachineController::class, 'update']);
    });
    Route::middleware('module.permission:machines,delete')->group(function () {
        Route::delete('machines/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');
    });
    
    // Machine status updates (operators and admins)
    Route::post('machines/{machine}/update-status', [MachineController::class, 'updateStatus'])->name('machines.update-status');
    
    // Work Orders - with module permission
    Route::middleware('module.permission:work_orders,view')->group(function () {
        Route::get('work-orders', [\App\Http\Controllers\WorkOrderController::class, 'index'])->name('work-orders.index');
    });
    Route::middleware('module.permission:work_orders,create')->group(function () {
        Route::get('work-orders/create', [\App\Http\Controllers\WorkOrderController::class, 'create'])->name('work-orders.create');
        Route::post('work-orders', [\App\Http\Controllers\WorkOrderController::class, 'store'])->name('work-orders.store');
    });
    Route::middleware('module.permission:work_orders,view')->group(function () {
        Route::get('work-orders/{workOrder}', [\App\Http\Controllers\WorkOrderController::class, 'show'])->name('work-orders.show');
    });
    Route::middleware('module.permission:work_orders,edit')->group(function () {
        Route::get('work-orders/{workOrder}/edit', [\App\Http\Controllers\WorkOrderController::class, 'edit'])->name('work-orders.edit');
        Route::put('work-orders/{workOrder}', [\App\Http\Controllers\WorkOrderController::class, 'update'])->name('work-orders.update');
        Route::patch('work-orders/{workOrder}', [\App\Http\Controllers\WorkOrderController::class, 'update']);
        Route::post('work-orders/{workOrder}/start', [\App\Http\Controllers\WorkOrderController::class, 'start'])->name('work-orders.start');
        Route::post('work-orders/{workOrder}/complete', [\App\Http\Controllers\WorkOrderController::class, 'complete'])->name('work-orders.complete');
        Route::post('work-orders/{workOrder}/assign', [\App\Http\Controllers\WorkOrderController::class, 'assign'])->name('work-orders.assign');
        Route::post('work-orders/{workOrder}/add-material', [\App\Http\Controllers\WorkOrderController::class, 'addMaterial'])->name('work-orders.add-material');
        Route::delete('work-orders/{workOrder}/remove-material/{consumption}', [\App\Http\Controllers\WorkOrderController::class, 'removeMaterial'])->name('work-orders.remove-material');
    });
    Route::middleware('module.permission:work_orders,delete')->group(function () {
        Route::delete('work-orders/{workOrder}', [\App\Http\Controllers\WorkOrderController::class, 'destroy'])->name('work-orders.destroy');
    });
    
    // Customers - always accessible for core workflow
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::patch('customers/{customer}/toggle', [\App\Http\Controllers\CustomerController::class, 'toggle'])->name('customers.toggle');
    Route::post('customers/{customer}/contacts', [\App\Http\Controllers\CustomerController::class, 'addContact'])->name('customers.contacts.store');
    Route::put('customers/{customer}/contacts/{contact}', [\App\Http\Controllers\CustomerController::class, 'updateContact'])->name('customers.contacts.update');
    Route::delete('customers/{customer}/contacts/{contact}', [\App\Http\Controllers\CustomerController::class, 'deleteContact'])->name('customers.contacts.destroy');
    
    // Invoices - always accessible for now (no invoices module in seeder)
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::post('invoices/{invoice}/mark-as-sent', [InvoiceController::class, 'markAsSent'])->name('invoices.mark-sent');
    Route::get('invoices/payments/{payment}/receipt', [InvoiceController::class, 'paymentReceipt'])->name('invoices.payments.receipt');
    
    // Payments
    Route::get('payments/record', [\App\Http\Controllers\PaymentController::class, 'record'])->name('payments.record');
    Route::post('payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');

    // Expenses - business owners only
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/aging', [\App\Http\Controllers\ReportsController::class, 'aging'])->name('reports.aging');
    Route::get('/reports/aging/export', [\App\Http\Controllers\ReportsController::class, 'agingExport'])->name('reports.aging.export');
    Route::get('/reports/expenses', [\App\Http\Controllers\ReportsController::class, 'expenses'])->name('reports.expenses');
    Route::get('/reports/profit-loss', [\App\Http\Controllers\ReportsController::class, 'profitLoss'])->name('reports.profit-loss');
    
    // Quotations - permissions handled in controller
    Route::middleware('auth')->group(function () {
        Route::resource('quotations', \App\Http\Controllers\QuotationController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        Route::get('quotations/{quotation}/pdf', [\App\Http\Controllers\QuotationController::class, 'pdf'])->name('quotations.pdf');
        Route::post('quotations/{quotation}/convert-to-invoice', [\App\Http\Controllers\QuotationController::class, 'convertToInvoice'])->name('quotations.convert');
        Route::post('quotations/{quotation}/mark-as-sent', [\App\Http\Controllers\QuotationController::class, 'markAsSent'])->name('quotations.mark-sent');
    });
    
    // Inventory - with module permission
    Route::middleware('module.permission:inventory,view')->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    });
    
    // Profile - always accessible
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Business Profile
    Route::get('/business/profile', [\App\Http\Controllers\BusinessController::class, 'profile'])->name('business.profile');
    Route::patch('/business/profile', [\App\Http\Controllers\BusinessController::class, 'updateProfile'])->name('business.profile.update');
    Route::post('/business/profile/preview', [\App\Http\Controllers\BusinessController::class, 'previewPDF'])->name('business.profile.preview');
    Route::post('/business/load-sample-data', [\App\Http\Controllers\BusinessController::class, 'loadSampleData'])->name('business.load-sample-data');
    
    // Dashboard onboarding
    Route::post('/dashboard/dismiss-onboarding', [DashboardController::class, 'dismissOnboarding'])->name('dashboard.dismiss-onboarding');
    
    // Activity Log - with module permission
    Route::middleware('module.permission:reports,view')->group(function () {
        Route::get('/activity-log', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log.index');
    });
    
    // Teams CRUD (for team management)
    Route::resource('teams', \App\Http\Controllers\TeamsController::class);

    // Team Management (Permission-based - checks done in controller)
    Route::get('/settings/team', [\App\Http\Controllers\TeamController::class, 'index'])->name('team.index');
    Route::post('/settings/team/invite', [\App\Http\Controllers\TeamController::class, 'invite'])->name('team.invite');
    Route::delete('/settings/team/invitations/{invitation}', [\App\Http\Controllers\TeamController::class, 'removeInvitation'])->name('team.remove-invitation');
    Route::delete('/settings/team/members/{user}', [\App\Http\Controllers\TeamController::class, 'removeMember'])->name('team.remove-member');
    Route::patch('/settings/team/members/{user}/permissions', [\App\Http\Controllers\TeamController::class, 'updatePermissions'])->name('team.update-permissions');
    Route::patch('/settings/team/members/{user}/status', [\App\Http\Controllers\TeamController::class, 'toggleStatus'])->name('team.toggle-status');
    Route::get('/settings/team/members/{user}/view-password', [\App\Http\Controllers\TeamController::class, 'viewPassword'])->name('team.view-password');
    Route::get('/team/performance', [\App\Http\Controllers\TeamController::class, 'performance'])->name('team.performance');
    Route::post('/team/performance', [\App\Http\Controllers\TeamController::class, 'performance'])->name('team.performance.post');
    
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Pricing page (accessible to all authenticated users)
Route::middleware('auth')->get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// API routes for states and cities
Route::get('/api/states', function () {
    $states = [
        ['id' => 'AP', 'name' => 'Andhra Pradesh'],
        ['id' => 'AR', 'name' => 'Arunachal Pradesh'],
        ['id' => 'AS', 'name' => 'Assam'],
        ['id' => 'BR', 'name' => 'Bihar'],
        ['id' => 'CT', 'name' => 'Chhattisgarh'],
        ['id' => 'GA', 'name' => 'Goa'],
        ['id' => 'GJ', 'name' => 'Gujarat'],
        ['id' => 'HR', 'name' => 'Haryana'],
        ['id' => 'HP', 'name' => 'Himachal Pradesh'],
        ['id' => 'JH', 'name' => 'Jharkhand'],
        ['id' => 'KA', 'name' => 'Karnataka'],
        ['id' => 'KL', 'name' => 'Kerala'],
        ['id' => 'MP', 'name' => 'Madhya Pradesh'],
        ['id' => 'MH', 'name' => 'Maharashtra'],
        ['id' => 'MN', 'name' => 'Manipur'],
        ['id' => 'ML', 'name' => 'Meghalaya'],
        ['id' => 'MZ', 'name' => 'Mizoram'],
        ['id' => 'NL', 'name' => 'Nagaland'],
        ['id' => 'OD', 'name' => 'Odisha'],
        ['id' => 'PB', 'name' => 'Punjab'],
        ['id' => 'RJ', 'name' => 'Rajasthan'],
        ['id' => 'SK', 'name' => 'Sikkim'],
        ['id' => 'TN', 'name' => 'Tamil Nadu'],
        ['id' => 'TG', 'name' => 'Telangana'],
        ['id' => 'TR', 'name' => 'Tripura'],
        ['id' => 'UP', 'name' => 'Uttar Pradesh'],
        ['id' => 'UK', 'name' => 'Uttarakhand'],
        ['id' => 'WB', 'name' => 'West Bengal'],
        ['id' => 'DL', 'name' => 'Delhi']
    ];
    return response()->json($states);
});

// API route for fetching cities by state name
Route::get('/api/cities/{stateName}', function ($stateName) {
    try {
        $state = \App\Models\State::where('name', $stateName)->first();
        
        if (!$state) {
            // Return empty array if state not found
            return response()->json([]);
        }
        
        $cities = $state->cities()->orderBy('name')->pluck('name');
        
        return response()->json($cities);
    } catch (\Exception $e) {
        \Log::error('Error fetching cities for state: ' . $stateName, ['error' => $e->getMessage()]);
        return response()->json([]);
    }
});

// API route for fetching vendor materials
Route::middleware('auth')->get('/api/vendors/{vendor}/materials', [VendorController::class, 'getMaterials']);

// API routes for vendor-material linkage
Route::middleware('auth')->post('/api/vendors/{vendor}/link-materials', [VendorController::class, 'linkMaterials']);
Route::middleware('auth')->get('/api/materials/{material}/vendors', [VendorController::class, 'getVendorsForMaterial']);
Route::middleware('auth')->get('/api/materials/{material}/vendors-for-po', [PurchaseOrderController::class, 'getVendorsForMaterial']);

// API route for fetching all materials (business-scoped)
Route::middleware('auth')->get('/materials/all', [MaterialController::class, 'getAllMaterials']);

// Global search API
Route::middleware('auth')->get('/api/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('api.search');

// API route for validating sales representative ID
Route::get('/api/validate-representative-id/{id}', function ($id) {
    $representative = \App\Models\SalesRepresentative::where('representative_id', $id)->first();

    if (!$representative) {
        return response()->json([
            'valid' => false,
            'message' => 'Invalid sales representative ID'
        ]);
    }

    if ($representative->status !== 'Active') {
        return response()->json([
            'valid' => false,
            'message' => 'Sales representative is not active'
        ]);
    }

    return response()->json([
        'valid' => true,
        'name' => $representative->full_name,
        'message' => 'Valid sales representative'
    ]);
});

// Debug routes
Route::post('/debug-login', function(\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    if (\Auth::attempt($credentials)) {
        return response()->json(['success' => true, 'user' => \Auth::user()->email]);
    }
    return response()->json(['success' => false, 'message' => 'Invalid credentials']);
});

Route::get('/debug-auth', function() {
    return response()->json([
        'authenticated' => \Auth::check(),
        'user' => \Auth::user() ? \Auth::user()->email : null
    ]);
});

// Test route without middleware
Route::get('/test-materials-create-simple', function() {
    return 'Materials create test - authenticated: ' . (\Auth::check() ? 'YES' : 'NO') . ' - User: ' . (\Auth::user() ? \Auth::user()->email : 'none');
});

// Test invoice route
Route::get('/test-invoices', function() {
    try {
        if (!\Auth::check()) {
            return 'Not authenticated';
        }

        $user = \Auth::user();
        $businessId = $user->business_id;

        if (!$businessId) {
            return 'No business ID for user: ' . $user->email;
        }

        $invoiceCount = \App\Models\Invoice::where('business_id', $businessId)->count();

        return 'User: ' . $user->email . ', Business ID: ' . $businessId . ', Invoice count: ' . $invoiceCount;
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Super Admin Routes (Hidden)
Route::prefix('superadmin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\SuperAdminLoginController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('/login', [\App\Http\Controllers\SuperAdminLoginController::class, 'login'])->name('superadmin.login.post');
    Route::post('/logout', [\App\Http\Controllers\SuperAdminLoginController::class, 'logout'])->name('superadmin.logout');

    Route::middleware('superadmin.auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
        Route::get('/contact-messages', [\App\Http\Controllers\SuperAdminContactController::class, 'index'])->name('superadmin.contact-messages');
        Route::delete('/contact-messages/{contactRequest}', [\App\Http\Controllers\SuperAdminContactController::class, 'destroy'])->name('superadmin.contact-messages.destroy');
        Route::post('/contact-messages/bulk-delete', [\App\Http\Controllers\SuperAdminContactController::class, 'bulkDelete'])->name('superadmin.contact-messages.bulk-delete');
        Route::get('/contact-messages/export', [\App\Http\Controllers\SuperAdminContactController::class, 'export'])->name('superadmin.contact-messages.export');
        Route::get('/business-owners', [\App\Http\Controllers\SuperAdminBusinessController::class, 'index'])->name('superadmin.business-owners');
        Route::resource('sales-representatives', \App\Http\Controllers\SuperAdminSalesRepresentativeController::class, ['except' => ['destroy'], 'as' => 'superadmin']);
        Route::post('/sales-representatives/{id}/toggle-status', [\App\Http\Controllers\SuperAdminSalesRepresentativeController::class, 'toggleStatus'])->name('superadmin.sales-representatives.toggle-status');
    });
});









