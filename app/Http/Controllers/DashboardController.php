<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use App\Models\InventoryBatch;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;

use App\Models\QualityAnalysis;
use App\Models\BarcodeLog;
use App\Models\StockMovement;
use App\Models\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->business_id) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Invalid account configuration.');
            }
            return $next($request);
        });
    }

    /** Display the dashboard */
    public function index()
    {
        try {
            $user = Auth::user();
            $businessId = $user->business_id;
            $subscriptionTier = $user->business->subscription_tier ?? 'full_erp';
            $subscriptionPlan = $user->business->subscription_plan ?? 'free';
            
            // Get unified stats for all users
            $stats = [
                'materials' => \App\Models\Material::where('business_id', $businessId)->count(),
                'vendors' => \App\Models\Vendor::where('business_id', $businessId)->count(),
                'purchase_orders' => \App\Models\PurchaseOrder::where('business_id', $businessId)->count(),
            ];
            
            // Add sales metrics for all users
            $salesMetrics = $this->getSalesMetrics($businessId, $subscriptionTier);
            
            // Only add machine/work order data for full_erp tier
            if ($subscriptionTier !== 'billing_sales') {
                $stats['machines'] = \App\Models\Machine::where('business_id', $businessId)->count();
                $stats['work_orders'] = \App\Models\WorkOrder::where('business_id', $businessId)->count();
                
                // Add production data
                $productionData = $this->getProductionData($businessId);
                $stats = array_merge($stats, $productionData);
            } else {
                // For billing_sales tier, add customer/invoice stats instead
                $stats['customers'] = \App\Models\Customer::where('business_id', $businessId)->count();
                $stats['invoices'] = \App\Models\Invoice::where('business_id', $businessId)->count();
                $stats['quotations'] = \App\Models\Quotation::where('business_id', $businessId)->count();
                $stats['quotations_this_month'] = \App\Models\Quotation::where('business_id', $businessId)->whereMonth('created_at', now())->count();
            }
            
            // Use the main dashboard view for all users
            return view('dashboard', compact('stats', 'subscriptionTier', 'subscriptionPlan', 'salesMetrics'));
            
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            // Fallback stats
            $stats = [
                'materials' => 0,
                'vendors' => 0,
                'purchase_orders' => 0,
                'customers' => 0,
                'invoices' => 0,
                'quotations' => 0,
            ];
            $salesMetrics = ['total_revenue' => 0, 'total_expenses' => 0, 'net_profit' => 0, 'outstanding_invoices' => 0, 'avg_payment_days' => 0, 'top_customers' => [], 'top_items' => []];
            $subscriptionTier = 'full_erp';
            $subscriptionPlan = 'free';
            return view('dashboard', compact('stats', 'subscriptionTier', 'subscriptionPlan', 'salesMetrics'));
        }
    }

    private function adminDashboard()
    {
        try {
            $businessId = auth()->user()->business_id;
            
            $stats = [
                'total_users' => \App\Models\User::where('business_id', $businessId)->count(),
                'materials' => \App\Models\Material::where('business_id', $businessId)->count(),
                'vendors' => \App\Models\Vendor::where('business_id', $businessId)->count(),
                'purchase_orders' => \App\Models\PurchaseOrder::where('business_id', $businessId)->count(),
                'machines' => \App\Models\Machine::where('business_id', $businessId)->count(),
                'work_orders' => \App\Models\WorkOrder::where('business_id', $businessId)->count(),
            ];
            
            // Add production data
            $productionData = $this->getProductionData($businessId);
            $stats = array_merge($stats, $productionData);
            
            $chartData = $this->getChartData();
            return view('dashboards.admin', compact('stats', 'chartData'));
        } catch (\Exception $e) {
            Log::error('Admin dashboard error: ' . $e->getMessage());
            // Fallback stats
            $stats = [
                'total_users' => 0,
                'materials' => 0,
                'vendors' => 0,
                'purchase_orders' => 0,
                'machines' => 0,
                'work_orders' => 0,
                'todays_work_orders' => 0,
                'machine_utilization' => 0,
                'production_value' => 0,
                'oee_score' => 0,
            ];
            return view('dashboards.admin', compact('stats'));
        }
    }

    private function managerDashboard()
    {
        $stats = [
            'pending_work_orders' => \App\Models\WorkOrder::where('business_id', auth()->user()->business_id)->where('status', 'pending')->count(),
            'active_work_orders' => \App\Models\WorkOrder::where('business_id', auth()->user()->business_id)->where('status', 'in_progress')->count(),
            'team_members' => \App\Models\User::where('business_id', auth()->user()->business_id)->where('role', '!=', 'admin')->count(),
            'low_stock_items' => $this->getLowStockCount(),
            'pending_approvals' => \App\Models\PurchaseOrder::where('business_id', auth()->user()->business_id)->where('status', 'pending')->count()
        ];
        $chartData = $this->getManagerChartData();
        return view('dashboards.manager', compact('stats', 'chartData'));
    }

    private function operatorDashboard()
    {
        $stats = [
            'my_work_orders' => \App\Models\WorkOrder::where('business_id', auth()->user()->business_id)->where('operator_id', auth()->id())->count(),
            'active_machines' => \App\Models\Machine::where('business_id', auth()->user()->business_id)->where('status', 'in_use')->count(),
            'completed_today' => \App\Models\WorkOrder::where('business_id', auth()->user()->business_id)->where('operator_id', auth()->id())->whereDate('completed_at', today())->count(),
            'materials_available' => \App\Models\InventoryBatch::whereHas('material', fn($q) => $q->where('business_id', auth()->user()->business_id))->where('current_quantity', '>', 0)->count()
        ];
        return view('dashboards.operator', compact('stats'));
    }

    private function viewerDashboard()
    {
        $stats = [
            'total_materials' => \App\Models\Material::where('business_id', auth()->user()->business_id)->count(),
            'total_work_orders' => \App\Models\WorkOrder::where('business_id', auth()->user()->business_id)->count(),
            'total_machines' => \App\Models\Machine::where('business_id', auth()->user()->business_id)->count(),
            'inventory_value' => \App\Models\InventoryBatch::whereHas('material', fn($q) => $q->where('business_id', auth()->user()->business_id))->sum(\DB::raw('current_quantity * unit_price'))
        ];
        return view('dashboards.viewer', compact('stats'));
    }

    private function inventoryManagerDashboard()
    {
        $stats = $this->getInventoryManagerStats();
        return view('dashboards.inventory-manager', compact('stats'));
    }

    private function purchaseTeamDashboard()
    {
        $stats = $this->getPurchaseTeamStats();
        return view('dashboards.purchase-team', compact('stats'));
    }

    /** Get navigation items based on user permissions - SIMPLIFIED */
    private function getNavigationItems($user): array
    {
        // Navigation is now handled by Gates in the view
        // This method is kept for backward compatibility
        return [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
                'active' => true,
            ],
        ];
    }
      
    private function getFirstWarehouseId($user)
    {
        // Get first warehouse ID with better error handling
        try {
            $warehouse = $user->warehouses()->first();
            if ($warehouse) {
                return $warehouse->id;
            }
            
            // Fallback to first available warehouse
            $firstWarehouse = Warehouse::first();
            return $firstWarehouse ? $firstWarehouse->id : 1;
        } catch (\Exception $e) {
            Log::error('Error getting first warehouse ID: ' . $e->getMessage());
            return 1; // Ultimate fallback
        }
    }

    /** Helper methods for permission checking - SIMPLIFIED */
    private function hasViewPermission($permission): bool
    {
        return (bool) ($permission->can_view ?? false);
    }

    private function hasEditPermission($permission): bool
    {
        return (bool) ($permission->can_edit ?? false);
    }

    /** Generate dashboard statistics based on user role */
    private function getDashboardStats($user)
    {
        try {
            $businessId = $user->business_id;
            
            // Get basic stats for multi-tenant context
            $stats = [];
            
            // Only query tables that exist
            try { $stats['materials'] = \App\Models\Material::where('business_id', '=', $businessId)->count(); } catch (\Exception $e) { $stats['materials'] = 0; }
            try { $stats['vendors'] = \App\Models\Vendor::where('business_id', '=', $businessId)->count(); } catch (\Exception $e) { $stats['vendors'] = 0; }
            try { $stats['purchase_orders'] = \App\Models\PurchaseOrder::where('business_id', '=', $businessId)->count(); } catch (\Exception $e) { $stats['purchase_orders'] = 0; }
            try { $stats['machines'] = \App\Models\Machine::where('business_id', '=', $businessId)->count(); } catch (\Exception $e) { $stats['machines'] = 0; }
            try { $stats['work_orders'] = \App\Models\WorkOrder::where('business_id', '=', $businessId)->count(); } catch (\Exception $e) { $stats['work_orders'] = 0; }

            // Add production data
            $productionData = $this->getProductionData($businessId);
            $stats = array_merge($stats, $productionData);
            
            $stats['user_role'] = $user->role ?? 'user';
            $stats['last_login'] = 'Welcome';

            return $stats;
        } catch (\Exception $e) {
            Log::error('Error generating dashboard stats: ' . $e->getMessage());
            return $this->getEmptyStats($user);
        }
    }
    
    private function getDefaultUserStats($user): array
    {
        $businessId = $user->business_id;
        
        return [
            'materials' => \App\Models\Material::where('business_id', $businessId)->count(),
            'vendors' => \App\Models\Vendor::where('business_id', $businessId)->count(),
            'purchase_orders' => \App\Models\PurchaseOrder::where('business_id', $businessId)->count(),
            'machines' => \App\Models\Machine::where('business_id', $businessId)->count(),
        ];
    }



/**
 * ✅ FIXED: Admin statistics with improved queries and debugging
 */
private function getAdminStats(): array
{
    try {
        $businessId = auth()->user()->business_id;
        
        $stats = [
            'total_users' => User::where('business_id', $businessId)->count(),
            'active_users' => User::where('business_id', $businessId)->where('is_active', true)->count(),
            'inactive_users' => User::where('business_id', $businessId)->where('is_active', false)->count(),
            'admin_users' => User::where('business_id', '=', $businessId)->where('role', '=', 'admin')->count(),
            'purchase_team_users' => User::where('business_id', '=', $businessId)->where('role', '=', 'purchase_team')->count(),
            'inventory_managers' => User::where('business_id', '=', $businessId)->where('role', '=', 'inventory_manager')->count(),

            // ✅ FIXED: Inventory stats with debugging
            'total_inventory_items' => InventoryBatch::whereHas('material', function($q) use ($businessId) { $q->where('business_id', $businessId); })->count(),
            'low_stock_items' => $this->getLowStockCount(),
            'out_of_stock' => InventoryBatch::whereHas('material', function($q) use ($businessId) { $q->where('business_id', $businessId); })->where('current_quantity', '<=', 0)->count(),

            'total_vendors' => Vendor::where('business_id', $businessId)->count(),
            'total_purchase_orders' => PurchaseOrder::where('business_id', $businessId)->count(),
            'pending_orders' => PurchaseOrder::where('business_id', $businessId)->where('status', 'pending')->count(),
            'total_warehouses' => $this->getTotalWarehouses(),
            
            // ✅ FIXED: Quality checks with better error handling
            'pending_quality_checks' => $this->getPendingQualityChecks(),
          'approved_quality_checks' => $this->getApprovedQualityChecks(),

            // ✅ FIXED: Recent logins - users who logged in within last 7 days
            'recent_logins' => $this->getRecentLogins(),

            'monthly_transactions' => $this->getMonthlyTransactions(),
        ];

        // Debug logging
        Log::info('Admin Dashboard Stats Generated:', $stats);
        
        return $stats;
    } catch (\Exception $e) {
        Log::error('Error in getAdminStats: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return $this->getEmptyAdminStats();
    }
}

  /**
 * ✅ Full stats method for Purchase Team Dashboard
 */
private function getPurchaseTeamStats(): array
{
    try {
        $businessId = auth()->user()->business_id;
        
        return [
            'total_orders'    => PurchaseOrder::where('business_id', $businessId)->count(),
            'pending_orders'  => PurchaseOrder::where('business_id', $businessId)->where('status', 'pending')->count(),
            'total_vendors'   => Vendor::where('business_id', $businessId)->count(),
            'budget_utilized' => $this->getBudgetUtilized(), // Implemented below
        ];
    } catch (\Exception $e) {
        Log::error('Error in getPurchaseTeamStats (Minimal): ' . $e->getMessage());
        return [
            'total_orders'    => 0,
            'pending_orders'  => 0,
            'total_vendors'   => 0,
            'budget_utilized' => 0,
        ];
    }
}
private function getBudgetUtilized(): float
{
    $businessId = auth()->user()->business_id;
    
    return PurchaseOrder::where('business_id', $businessId)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_amount');
}

  
/**
 * ✅ FIXED: Inventory Manager statistics with debugging
 */
private function getInventoryManagerStats(): array
{
    try {
        $businessId = auth()->user()->business_id;
        
        $stats = [
            'total_items' => InventoryBatch::whereHas('material', function($q) use ($businessId) { $q->where('business_id', $businessId); })->count(),
            'low_stock_items' => $this->getLowStockCount(),
            'out_of_stock' => InventoryBatch::whereHas('material', function($q) use ($businessId) { $q->where('business_id', $businessId); })->where('current_quantity', '<=', 0)->count(),
            'items_added_today' => InventoryBatch::whereHas('material', function($q) use ($businessId) { $q->where('business_id', $businessId); })->whereDate('created_at', Carbon::today())->count(),
            'items_updated_today' => InventoryBatch::whereHas('material', function($q) use ($businessId) { $q->where('business_id', $businessId); })->whereDate('updated_at', Carbon::today())
                ->where('updated_at', '>', DB::raw('created_at'))
                ->count(),
            'total_warehouses' => Warehouse::count(),
            'pending_material_requests' => 0,
        ];

        // Debug logging
        Log::info('Inventory Manager Dashboard Stats Generated:', $stats);
        
        return $stats;
    } catch (\Exception $e) {
        Log::error('Error in getInventoryManagerStats: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return $this->getEmptyInventoryStats();
    }
}

/**
 * ✅ FIXED: Low stock count with multiple approaches and debugging
 */
private function getLowStockCount(): int
{
    try {
        $businessId = auth()->user()->business_id;
        
        // Method 1: Simple query with business scoping
        $lowStockCount = InventoryBatch::whereHas('material', function($q) use ($businessId) {
                $q->where('business_id', $businessId);
            })
            ->where('current_quantity', '<=', 10)
            ->where('current_quantity', '>', 0)
            ->count();
        
        Log::info("Low stock count (Method 1): $lowStockCount");

        // Method 2: Alternative query if first one returns 0
        if ($lowStockCount === 0) {
            $lowStockCount = InventoryBatch::whereHas('material', function($q) use ($businessId) {
                    $q->where('business_id', $businessId);
                })
                ->whereBetween('current_quantity', [1, 10])
                ->count();
            Log::info("Low stock count (Method 2): $lowStockCount");
        }

        return $lowStockCount;
        
    } catch (\Exception $e) {
        Log::error('Error getting low stock count: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return 0;
    }
}

/**
 * ✅ FIXED: Recent logins with multiple approaches and debugging
 */
private function getRecentLogins(): int
{
    try {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        
        // Method 1: Check for last_login_at column
        $recentLogins = User::where('last_login_at', '>=', $sevenDaysAgo)
            ->whereNotNull('last_login_at')
            ->count();
        
        Log::info("Recent logins (Method 1): $recentLogins");
        Log::info("Date filter: $sevenDaysAgo");

        // Method 2: Alternative - use updated_at if last_login_at doesn't exist or is null
        if ($recentLogins === 0) {
            $recentLogins = User::where('updated_at', '>=', $sevenDaysAgo)->count();
            Log::info("Recent logins (Method 2 - updated_at): $recentLogins");
        }

        // Debug: Check if last_login_at column exists and has data
        $usersWithLastLogin = User::whereNotNull('last_login_at')->count();
        $totalUsers = User::count();
        Log::info("Debug - Total users: $totalUsers, Users with last_login_at: $usersWithLastLogin");

        // Sample a user to check the data structure
        $sampleUser = User::first();
        if ($sampleUser) {
            Log::info("Sample user last_login_at: " . $sampleUser->last_login_at);
            Log::info("Sample user attributes:", array_keys($sampleUser->getAttributes()));
        }

        return $recentLogins;
        
    } catch (\Exception $e) {
        Log::error('Error getting recent logins: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return 0;
    }
}

private function getPendingQualityChecks(): int
{
    try {
        $count = QualityAnalysis::where('quality_status', 'pending')->count();
        Log::info("Pending Quality Check Count: $count");
        return $count;
    } catch (\Exception $e) {
        Log::error('Error fetching pending quality checks: ' . $e->getMessage());
        return 0;
    }
}

private function getApprovedQualityChecks(): int
{
    try {
        $count = QualityAnalysis::where('quality_status', 'approved')->count();
        Log::info("Approved Quality Check Count: $count");
        return $count;
    } catch (\Exception $e) {
        Log::error('Error fetching approved quality checks: ' . $e->getMessage());
        return 0;
    }
}


/**
 * Debug method to check database structure and data
 */
public function debugDashboardStats()
{
    if (!Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }

    $debug = [];
    
    try {
        // Check InventoryBatch table
        $debug['inventory'] = [
            'total_count' => InventoryBatch::count(),
            'with_quantity' => InventoryBatch::whereNotNull('current_quantity')->count(),
            'sample_quantities' => InventoryBatch::take(5)->pluck('current_quantity')->toArray(),
            'quantity_stats' => [
                'min' => InventoryBatch::min('current_quantity'),
                'max' => InventoryBatch::max('current_quantity'),
                'avg' => round(InventoryBatch::avg('current_quantity') ?? 0, 2),
            ]
        ];

        // Check Users table
        $debug['users'] = [
            'total_count' => User::count(),
            'with_last_login' => User::whereNotNull('last_login_at')->count(),
            'recent_7_days' => User::where('last_login_at', '>=', Carbon::now()->subDays(7))->count(),
            'sample_last_logins' => User::whereNotNull('last_login_at')->take(3)->pluck('last_login_at')->toArray(),
        ];

        // Check QualityAnalysis table
        $debug['quality'] = [
            'total_count' => QualityAnalysis::count(),
            'status_distribution' => QualityAnalysis::groupBy('quality_status')->selectRaw('quality_status, count(*) as count')->get()->pluck('count', 'quality_status')->toArray(),
            'columns' => QualityAnalysis::first()?->getAttributes() ? array_keys(QualityAnalysis::first()->getAttributes()) : [],
        ];
      // Check PurchaseOrder table
$debug['purchase_orders'] = [
    'total' => PurchaseOrder::count(),
    'status_counts' => PurchaseOrder::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray(),
    'recent_orders' => PurchaseOrder::latest()->take(3)->get(['id', 'status', 'total_amount', 'created_at'])->toArray(),
    'in_current_month' => PurchaseOrder::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count(),
    'budget_utilized' => $this->getBudgetUtilized(), // ✅ Add your actual logic
];

// Check Vendor table
$debug['vendors'] = [
    'total' => Vendor::count(),
    'sample_names' => Vendor::take(3)->pluck('name')->toArray(),
];


    } catch (\Exception $e) {
        $debug['error'] = $e->getMessage();
    }

    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
}


  

    /** Empty stats fallback methods */
    private function getEmptyStats($user): array
    {
        return [
            'user_role' => $user->role ?? 'user',
            'last_login' => 'Welcome',
            'error' => 'Unable to load statistics'
        ];
    }

    private function getEmptyAdminStats(): array
    {
        return [
            'total_users' => 0,
            'active_users' => 0,
            'total_vendors' => 0,
            'total_inventory_items' => 0,
            'low_stock_items' => 0,
            'out_of_stock' => 0,
            'total_purchase_orders' => 0,
            'pending_orders' => 0,
            'total_warehouses' => 0,
            'pending_quality_checks' => 0,
            'recent_logins' => 0,
            'monthly_transactions' => 0,
        ];
    }

  

    private function getEmptyInventoryStats(): array
    {
        return [
            'total_items' => 0,
            'low_stock_items' => 0,
            'out_of_stock' => 0,
            'total_warehouses' => 0,
            'pending_material_requests' => 0,
        ];
    }

    /** Show users management page - Admin only */
    public function showUsers(Request $request)
    {
        $this->authorizeAdmin();

        try {
            $query = User::query();

            // Apply search filters
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('role')) {
                $query->where('role', '=', $request->role);
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', '=', $request->is_active);
            }

            $users = $query->latest()->paginate(15);

            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', '=', 1)->count(),
                'inactive_users' => User::where('is_active', '=', 0)->count(),
                'total_vendors' => Vendor::count(),
                'total_items' => InventoryItem::count(),
                'total_orders' => PurchaseOrder::count(),
            ];

            return view('dashboard.users', compact('users', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error in showUsers: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Unable to load users page.');
        }
    }

    /** Store a new user - Admin only */
    public function storeUser(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,purchase_team,inventory_manager,user',
            'is_active' => 'required|boolean',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
                'phone' => $validated['phone'],
                'email_verified_at' => now(),
            ]);

            // Assign default permissions based on role
            $this->assignDefaultPermissions($newUser);

            DB::commit();

            Log::info('User created successfully', ['user_id' => $newUser->id, 'created_by' => Auth::id()]);

            return response()->json([
                'success' => true, 
                'message' => 'User created successfully!', 
                'user' => $newUser->only(['id', 'name', 'email', 'role', 'is_active'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign default permissions to a new user based on their role
     */
    private function assignDefaultPermissions(User $user)
    {
        try {
            // Don't assign permissions to admin users - they have access to everything
            if ($user->role === 'admin') {
                return;
            }

            // Get modules based on role
            $modulePermissions = $this->getDefaultModulePermissions($user->role);
            
            foreach ($modulePermissions as $moduleName => $permissions) {
                // Find the module by name
                $module = Module::where('name', '=', $moduleName)->first();
                
                if ($module) {
                    // Check if permission already exists
                    $existingPermission = $user->permissions()->where('module_id', $module->id)->first();
                    
                    if (!$existingPermission) {
                        // Create permission record
                        $user->permissions()->create([
                            'module_id' => $module->id,
                            'can_view' => $permissions['can_view'],
                            'can_edit' => $permissions['can_edit'],
                        ]);
                        
                        Log::info("Assigned permission for module {$moduleName} to user {$user->email}");
                    } else {
                        Log::info("Permission for module {$moduleName} already exists for user {$user->email}");
                    }
                } else {
                    Log::warning("Module {$moduleName} not found when assigning permissions to user {$user->email}");
                }
            }
            
            Log::info("Default permissions assigned to user {$user->email}");
        } catch (\Exception $e) {
            Log::error("Error assigning default permissions to user {$user->email}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fix permissions for existing users who don't have any - Admin only
     */
    public function fixUserPermissions(Request $request)
    {
        $this->authorizeAdmin();
        
        try {
            $usersWithoutPermissions = User::whereDoesntHave('permissions')
                ->where('role', '!=', 'admin')
                ->get();
            
            $fixedCount = 0;
            
            foreach ($usersWithoutPermissions as $user) {
                $this->assignDefaultPermissions($user);
                $fixedCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Fixed permissions for {$fixedCount} users",
                'users_fixed' => $fixedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error fixing user permissions: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fix user permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get default module permissions for each role
     */
    private function getDefaultModulePermissions($role): array
    {
        switch ($role) {
            case 'purchase_team':
                return [
                    'vendor_management' => ['can_view' => true, 'can_edit' => true],
                    'purchase_orders' => ['can_view' => true, 'can_edit' => true],
                    'materials' => ['can_view' => true, 'can_edit' => false],
                ];
                
            case 'inventory_manager':
                return [
                    'inventory_control' => ['can_view' => true, 'can_edit' => true],
                    'warehouse_management' => ['can_view' => true, 'can_edit' => true],
                    'barcode_management' => ['can_view' => true, 'can_edit' => true],
                    'materials' => ['can_view' => true, 'can_edit' => true],
                    'quality_analysis' => ['can_view' => true, 'can_edit' => true],
                ];
                
            case 'user':
                return [
                    'materials' => ['can_view' => true, 'can_edit' => false],
                    'inventory_control' => ['can_view' => true, 'can_edit' => false],
                ];
                
            default:
                return [];
        }
    }

    /** Update user active status - Admin only */
    public function updateUserStatus(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        try {
            $user->update(['is_active' => $validated['is_active']]);

            Log::info('User status updated', [
                'user_id' => $user->id,
                'new_status' => $validated['is_active'],
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'User activation status updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user status: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update user status'
            ], 500);
        }
    }

    /** Deactivate a user - Admin only */
    public function deleteUser(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->id === $currentUser->id) {
            return response()->json([
                'error' => 'You cannot deactivate your own account'
            ], 400);
        }

        try {
            $user->update(['is_active' => 0]);
            
            Log::info('User deactivated', [
                'user_id' => $user->id,
                'deactivated_by' => $currentUser->id
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'User deactivated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deactivating user: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to deactivate user: ' . $e->getMessage()
            ], 500);
        }
    }

    /** Helper authorization for admin */
    private function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    /** ✅ FIXED: Helper methods for statistics with improved error handling */
    private function getProfileCompletion($user): int 
    { 
        $completion = 50; // Base completion
        if ($user->email_verified_at) $completion += 20;
        if ($user->phone) $completion += 15;
        if ($user->last_login_at) $completion += 15;
        return min($completion, 100);
    }

    private function getTotalWarehouses(): int 
    { 
        try {
            return Warehouse::count();
        } catch (\Exception $e) {
            Log::error('Error getting total warehouses: ' . $e->getMessage());
            return 0;
        }
    }

    private function getMonthlyBudget(): int 
    { 
        // This should come from settings or budget table
        return 150000; 
    }


  

    private function getMonthlyTransactions(): int
    {
        try {
            $businessId = auth()->user()->business_id;
            
            return PurchaseOrder::where('business_id', $businessId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } catch (\Exception $e) {
            Log::error('Error getting monthly transactions: ' . $e->getMessage());
            return 0;
        }
    }
    /** Debug method for permissions troubleshooting */
    public function debugPermissions()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        
        $output = "<h3>User Information:</h3>";
        $output .= "Name: " . $user->name . "<br>";
        $output .= "Email: " . $user->email . "<br>";
        $output .= "Role: " . $user->role . "<br>";
        $output .= "Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No') . "<br><br>";
        
        $output .= "<h3>User Permissions:</h3>";
        $permissions = $user->permissions()->with('module')->get();
        
        if ($permissions->isEmpty()) {
            $output .= "No permissions found for this user.<br>";
            $output .= "Providing default access based on role: " . $user->role . "<br>";
        } else {
            $output .= "<table border='1' style='border-collapse: collapse;'>";
            $output .= "<tr><th style='padding: 8px;'>Module</th><th style='padding: 8px;'>Can View</th><th style='padding: 8px;'>Can Edit</th></tr>";
            foreach ($permissions as $permission) {
                $output .= "<tr>";
                $output .= "<td style='padding: 8px;'>" . ($permission->module ? $permission->module->name : 'No Module') . "</td>";
                $output .= "<td style='padding: 8px;'>" . ($permission->can_view ? 'Yes' : 'No') . "</td>";
                $output .= "<td style='padding: 8px;'>" . ($permission->can_edit ? 'Yes' : 'No') . "</td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
        }
        
        $output .= "<h3>Available Modules:</h3>";
        $modules = Module::where('is_active', '=', true)->get();
        $output .= "<table border='1' style='border-collapse: collapse;'>";
        $output .= "<tr><th style='padding: 8px;'>ID</th><th style='padding: 8px;'>Name</th><th style='padding: 8px;'>Display Name</th><th style='padding: 8px;'>Is Active</th></tr>";
        foreach ($modules as $module) {
            $output .= "<tr>";
            $output .= "<td style='padding: 8px;'>" . $module->id . "</td>";
            $output .= "<td style='padding: 8px;'>" . $module->name . "</td>";
            $output .= "<td style='padding: 8px;'>" . $module->display_name . "</td>";
            $output .= "<td style='padding: 8px;'>" . ($module->is_active ? 'Yes' : 'No') . "</td>";
            $output .= "</tr>";
        }
        $output .= "</table>";

        return response($output);
    }

    private function getChartData()
    {
        $businessId = auth()->user()->business_id;
        
        return [
            'workOrdersChart' => [
                'labels' => ['Pending', 'In Progress', 'Completed'],
                'data' => [
                    \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'pending')->count(),
                    \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'in_progress')->count(),
                    \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'completed')->count()
                ],
                'colors' => ['#fbbf24', '#3b82f6', '#10b981']
            ],
            'monthlyTrends' => $this->getMonthlyTrends(),
            'machineUtilization' => $this->getMachineUtilization()
        ];
    }

    private function getManagerChartData()
    {
        $businessId = auth()->user()->business_id;
        
        return [
            'teamPerformance' => $this->getTeamPerformanceData(),
            'workOrderTrends' => $this->getWorkOrderTrends(),
            'inventoryStatus' => $this->getInventoryStatusData()
        ];
    }

    private function getMonthlyTrends()
    {
        $businessId = auth()->user()->business_id;
        $months = [];
        $workOrders = [];
        $purchaseOrders = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $workOrders[] = \App\Models\WorkOrder::where('business_id', $businessId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $purchaseOrders[] = \App\Models\PurchaseOrder::where('business_id', $businessId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return [
            'labels' => $months,
            'workOrders' => $workOrders,
            'purchaseOrders' => $purchaseOrders
        ];
    }

    private function getMachineUtilization()
    {
        $businessId = auth()->user()->business_id;
        
        $machines = \App\Models\Machine::where('business_id', $businessId)->get();
        $labels = [];
        $data = [];
        
        foreach ($machines as $machine) {
            $labels[] = $machine->name;
            $utilization = \App\Models\WorkOrder::where('business_id', $businessId)
                ->where('machine_id', $machine->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->count();
            $data[] = $utilization;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getTeamPerformanceData()
    {
        $businessId = auth()->user()->business_id;
        
        $operators = \App\Models\User::where('business_id', $businessId)
            ->where('role', 'operator')
            ->get();
            
        $labels = [];
        $data = [];
        
        foreach ($operators as $operator) {
            $labels[] = $operator->name;
            $completed = \App\Models\WorkOrder::where('business_id', $businessId)
                ->where('assigned_to', $operator->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->count();
            $data[] = $completed;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getWorkOrderTrends()
    {
        $businessId = auth()->user()->business_id;
        $days = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('M j');
            
            $count = \App\Models\WorkOrder::where('business_id', $businessId)
                ->whereDate('created_at', $date)
                ->count();
            $data[] = $count;
        }
        
        return [
            'labels' => $days,
            'data' => $data
        ];
    }

    private function getInventoryStatusData()
    {
        $businessId = auth()->user()->business_id;
        
        $inStock = \App\Models\InventoryBatch::whereHas('material', fn($q) => $q->where('business_id', $businessId))
            ->where('current_quantity', '>', 10)
            ->count();
            
        $lowStock = $this->getLowStockCount();
        
        $outOfStock = \App\Models\InventoryBatch::whereHas('material', fn($q) => $q->where('business_id', $businessId))
            ->where('current_quantity', '<=', 0)
            ->count();
        
        return [
            'labels' => ['In Stock', 'Low Stock', 'Out of Stock'],
            'data' => [$inStock, $lowStock, $outOfStock],
            'colors' => ['#10b981', '#f59e0b', '#ef4444']
        ];
    }

    public function getRealtimeData()
    {
        $businessId = auth()->user()->business_id;
        
        return response()->json([
            'workOrders' => [
                'pending' => \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'pending')->count(),
                'inProgress' => \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'in_progress')->count(),
                'completed' => \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'completed')->count()
            ],
            'machines' => [
                'available' => \App\Models\Machine::where('business_id', $businessId)->where('status', 'available')->count(),
                'inUse' => \App\Models\Machine::where('business_id', $businessId)->where('status', 'in_use')->count(),
                'maintenance' => \App\Models\Machine::where('business_id', $businessId)->where('status', 'maintenance')->count()
            ],
            'production' => $this->getProductionData($businessId),
            'timestamp' => now()->toISOString()
        ]);
    }
    
    private function getProductionData($businessId)
    {
        try {
            $today = now()->startOfDay();
            
            // Today's work orders
            $todaysWorkOrders = \App\Models\WorkOrder::where('business_id', $businessId)
                ->whereDate('created_at', $today)
                ->count();
            
            // Yesterday's work orders for comparison
            $yesterdaysWorkOrders = \App\Models\WorkOrder::where('business_id', $businessId)
                ->whereDate('created_at', $today->copy()->subDay())
                ->count();
            
            $workOrderChange = $todaysWorkOrders - $yesterdaysWorkOrders;
            
            // Machine utilization (percentage of machines in use)
            $totalMachines = \App\Models\Machine::where('business_id', $businessId)->count();
            $machinesInUse = \App\Models\Machine::where('business_id', $businessId)
                ->where('status', 'in_use')
                ->count();
            
            $machineUtilization = $totalMachines > 0 ? round(($machinesInUse / $totalMachines) * 100) : 0;
            
            // Production value (sum of completed work orders today)
            $productionValue = \App\Models\WorkOrder::where('business_id', $businessId)
                ->where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->sum('estimated_cost') ?? 0;
            
            // OEE calculation (simplified)
            $completedToday = \App\Models\WorkOrder::where('business_id', $businessId)
                ->where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->count();
            
            $totalToday = \App\Models\WorkOrder::where('business_id', $businessId)
                ->whereDate('created_at', $today)
                ->count();
            
            $oeeScore = $totalToday > 0 ? round(($completedToday / $totalToday) * 100) : 0;
            
            return [
                'todays_work_orders' => $todaysWorkOrders,
                'work_order_change' => $workOrderChange,
                'machine_utilization' => $machineUtilization,
                'production_value' => $productionValue,
                'oee_score' => max(65, $oeeScore), // Minimum 65% to show realistic OEE
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting production data: ' . $e->getMessage());
            return [
                'todays_work_orders' => 0,
                'work_order_change' => 0,
                'machine_utilization' => 0,
                'production_value' => 0,
                'oee_score' => 0,
            ];
        }
    }
    
    public function dismissOnboarding(Request $request)
    {
        session(['onboarding_dismissed' => true]);
        return response()->json(['success' => true]);
    }
    
    private function getSalesMetrics($businessId, $subscriptionTier)
    {
        try {
            $currentMonth = now()->startOfMonth();

            // Total Revenue (This Month) - using issue_date instead of paid_date
            $totalRevenue = \App\Models\Invoice::where('business_id', $businessId)
                ->where('status', 'paid')
                ->where('issue_date', '>=', $currentMonth)
                ->sum('total_amount') ?? 0;

            // Total Expenses (This Month)
            $totalExpenses = \App\Models\Expense::where('business_id', $businessId)
                ->where('expense_date', '>=', $currentMonth)
                ->sum('amount') ?? 0;

            // Net Profit (This Month)
            $netProfit = $totalRevenue - $totalExpenses;

            // Outstanding Invoices
            $outstandingInvoices = \App\Models\Invoice::where('business_id', $businessId)
                ->where('status', '!=', 'paid')
                ->sum('total_amount') ?? 0;

            // Average Payment Days - simplified calculation
            $paidInvoices = \App\Models\Invoice::where('business_id', $businessId)
                ->where('status', 'paid')
                ->whereNotNull('paid_date')
                ->whereNotNull('issue_date')
                ->get();

            $avgPaymentDays = 0;
            if ($paidInvoices->count() > 0) {
                $totalDays = $paidInvoices->sum(function($invoice) {
                    return $invoice->paid_date->diffInDays($invoice->issue_date);
                });
                $avgPaymentDays = round($totalDays / $paidInvoices->count());
            }

            // Top 3 Customers (by revenue)
            $topCustomers = \App\Models\Invoice::where('business_id', $businessId)
                ->where('status', 'paid')
                ->selectRaw('customer_name, SUM(total_amount) as total_revenue')
                ->groupBy('customer_name')
                ->orderByDesc('total_revenue')
                ->limit(3)
                ->get();

            $result = [
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit,
                'outstanding_invoices' => $outstandingInvoices,
                'avg_payment_days' => $avgPaymentDays,
                'top_customers' => $topCustomers,
                'top_items' => []
            ];

            // Top 3 Items (only for full_erp users)
            if ($subscriptionTier !== 'billing_sales') {
                try {
                    $topItems = \App\Models\InvoiceItem::whereHas('invoice', function($q) use ($businessId) {
                            $q->where('business_id', $businessId)->where('status', 'paid');
                        })
                        ->selectRaw('description, SUM(total_price) as total_revenue')
                        ->groupBy('description')
                        ->orderByDesc('total_revenue')
                        ->limit(3)
                        ->get();

                    $result['top_items'] = $topItems;
                } catch (\Exception $e) {
                    $result['top_items'] = [];
                }
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Error getting sales metrics: ' . $e->getMessage());
            return [
                'total_revenue' => 0,
                'total_expenses' => 0,
                'net_profit' => 0,
                'outstanding_invoices' => 0,
                'avg_payment_days' => 0,
                'top_customers' => [],
                'top_items' => []
            ];
        }
    }
  
}