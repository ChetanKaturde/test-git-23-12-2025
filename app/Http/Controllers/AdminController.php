<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-users');
    }

    public function users()
    {
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Admin Dashboard with Notifications
     */
    public function dashboard()
    {
        // Get all notifications for current admin user
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get unread notifications count
        $unreadCount = Auth::user()->unreadNotifications()->count();

        // Get recent activities (Purchase Orders)
        $recentOrders = PurchaseOrder::with(['vendor', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get statistics with caching
        $stats = \Cache::remember('admin_dashboard_stats', 300, function() {
            $orders = PurchaseOrder::get(['status', 'created_at']);
            $users = User::get(['is_active']);
            return [
                'total_orders' => $orders->count(),
                'pending_orders' => $orders->where('status', 'pending')->count(),
                'approved_orders' => $orders->where('status', 'approved')->count(),
                'total_users' => $users->count(),
                'active_users' => $users->where('is_active', 1)->count(),
                'recent_orders_count' => $orders->where('created_at', '>=', today())->count(),
            ];
        });

        return view('dashboard.admin.index', compact(
            'notifications', 
            'unreadCount', 
            'recentOrders', 
            'stats'
        ));
    }

    /**
     * Show all notifications page
     */
    public function notifications(Request $request)
    {
        $query = Auth::user()->notifications();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        // Get notification statistics with single query
        $user = Auth::user();
        $notificationStats = Cache::remember("notification_stats_{$user->id}", 300, function() use ($user) {
            $notifications = $user->notifications()->get(['created_at', 'read_at']);
            return [
                'total' => $notifications->count(),
                'unread' => $notifications->whereNull('read_at')->count(),
                'today' => $notifications->where('created_at', '>=', today())->count(),
                'this_week' => $notifications->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->count(),
            ];
        });

        return view('dashboard.admin.notifications', compact(
            'notifications', 
            'notificationStats'
        ));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function deleteNotification($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Get notifications for AJAX requests (for real-time updates)
     */
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'is_read' => !is_null($notification->read_at),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    public function index(Request $request)
    {
        // Build filtered query
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(15)->appends($request->query());

        // Stats with single query
        $userStats = Cache::remember('user_stats', 300, function() {
            $users = User::get(['is_active', 'role', 'created_at']);
            return [
                'total' => $users->count(),
                'active' => $users->where('is_active', 1)->count(),
                'inactive' => $users->where('is_active', 0)->count(),
                'admin' => $users->where('role', 'admin')->count(),
                'recent' => $users->where('created_at', '>=', now()->startOfMonth())->count(),
            ];
        });
        
        $totalUsers = $userStats['total'];
        $activeUsers = $userStats['active'];
        $inactiveUsers = $userStats['inactive'];
        $adminUsers = $userStats['admin'];
        $recentUsers = $userStats['recent'];

        return view('dashboard.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'adminUsers',
            'recentUsers'
        ));
    }

    /**
     * Show create form with modules for permissions
     */
    public function create()
    {
        $roles = $this->roles();
        $modules = Module::where('is_active', '=', true)->get();
        return view('dashboard.users.create', compact('roles', 'modules'));
    }



// Update the store method in AdminController

public function store(Request $request)
{
    $validated = $this->validateUser($request);

    try {
        DB::beginTransaction();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $validated['status'] === 'active',
        ]);

        // Handle permissions if provided - bulk insert for performance
        if ($request->has('permissions')) {
            $permissionsData = [];
            foreach ($request->permissions ?? [] as $moduleId => $permission) {
                if (!empty($permission['view']) || !empty($permission['edit']) || !empty($permission['create']) || !empty($permission['delete'])) {
                    $permissionsData[] = [
                        'user_id' => $user->id,
                        'module_id' => $moduleId,
                        'can_view' => !empty($permission['view']),
                        'can_edit' => !empty($permission['edit']),
                        'can_create' => !empty($permission['create']),
                        'can_delete' => !empty($permission['delete']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if (!empty($permissionsData)) {
                Permission::insert($permissionsData);
            }
        }

        DB::commit();

        return $this->handleSuccess('User created successfully.', $request);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('User creation failed: ' . $e->getMessage());
        return $this->handleError('Failed to create user: ' . $e->getMessage(), $request);
    }
}

// Also update the updatePermissions method
public function updatePermissions(Request $request, User $user)
{
    $request->validate([
        'permissions' => 'array',
        'permissions.*' => 'array',
        'permissions.*.view' => 'boolean',
        'permissions.*.edit' => 'boolean',
        'permissions.*.create' => 'boolean',
        'permissions.*.delete' => 'boolean',
    ]);

    try {
        DB::beginTransaction();

        // Clear existing permissions
        $user->permissions()->delete();

        // Add new permissions - bulk insert for performance
        $permissionsData = [];
        foreach ($request->permissions ?? [] as $moduleId => $permission) {
            if (!empty($permission['view']) || !empty($permission['edit']) || !empty($permission['create']) || !empty($permission['delete'])) {
                $permissionsData[] = [
                    'user_id' => $user->id,
                    'module_id' => $moduleId,
                    'can_view' => !empty($permission['view']),
                    'can_edit' => !empty($permission['edit']),
                    'can_create' => !empty($permission['create']),
                    'can_delete' => !empty($permission['delete']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if (!empty($permissionsData)) {
            Permission::insert($permissionsData);
        }

        DB::commit();

        return $this->handleSuccess('User permissions updated successfully.', $request, 'dashboard.users.index');

    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Permission update failed for user ID {$user->id}: " . $e->getMessage());
        return $this->handleError('Failed to update permissions: ' . $e->getMessage(), $request);
    }
}
    /**
     * Show user details.
     */
    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Edit user form with modules and permissions
     */
    public function edit(User $user)
    {
        $roles = $this->roles();
        $modules = Module::where('is_active', '=', true)->get();
        
        // Single query to get all permissions
        $permissions = $user->permissions()->get(['module_id', 'can_edit', 'can_view']);
        $userPermissions = $permissions->pluck('can_edit', 'module_id')->toArray();
        $userViewPermissions = $permissions->pluck('can_view', 'module_id')->toArray();
        
        return view('dashboard.users.edit', compact('user', 'roles', 'modules', 'userPermissions', 'userViewPermissions'));
    }

    /**
     * Update user details (name, email, role, password, and optionally status).
     */
    public function update(Request $request, User $user)
    {
        $validated = $this->validateUser($request, $user->id);

        try {
            $data = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
                'role'  => $validated['role'],
            ];

            // Handle status only if it was included in the request (e.g., from form)
            if (isset($validated['status'])) {
                $data['is_active'] = $validated['status'] === 'active';
            }

            // Handle password update if provided
            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            // Perform update
            $user->update($data);

            return $this->handleSuccess('User updated successfully.', $request);

        } catch (\Exception $e) {
            Log::error("User update failed for ID {$user->id}: " . $e->getMessage());

            return $this->handleError('Failed to update user.', $request);
        }
    }

    /**
     * Show user permissions form
     */
    public function permissions(User $user)
    {
        $modules = Module::where('is_active', '=', true)->get();
        
        // Single query to get all permissions
        $permissions = $user->permissions()->get(['module_id', 'can_edit', 'can_view']);
        $userPermissions = $permissions->pluck('can_edit', 'module_id')->toArray();
        $userViewPermissions = $permissions->pluck('can_view', 'module_id')->toArray();
        
        return view('dashboard.users.permissions', compact('user', 'modules', 'userPermissions', 'userViewPermissions'));
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('dashboard.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        try {
            // Optional: Only if using policies
            // $this->authorize('toggleStatus', $user);

            // Validate the input
            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            // Update the is_active field (true = 1, false = 0)
            $user->update([
                'is_active' => $request->status === 'active',
            ]);

            return response()->json([
                'success' => true,
                'status' => $request->status,
                'message' => "User status updated to {$request->status}.",
            ]);

        } catch (\Exception $e) {
            Log::error("Toggle status failed for user ID {$user->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while updating the user status.'
            ], 500);
        }
    }

    /**
     * Shared roles.
     */
    private function roles()
    {
        return [
            'admin' => 'Administrator',
            'purchase_team' => 'Purchase Team',
            'inventory_manager' => 'Inventory Manager',
            'user' => 'User',
        ];
    }

    /**
     * Unified user validation.
     */
    private function validateUser(Request $request, $userId = null)
    {
        $uniqueEmailRule = Rule::unique('users')->ignore($userId);

        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', $uniqueEmailRule],
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,purchase_team,inventory_manager,user',
            'status' => 'required|in:active,inactive',
            'send_welcome_email' => 'boolean',
        ]);
    }

    /**
     * Handle successful responses.
     */
    private function handleSuccess($message, $request, $redirectRoute = 'dashboard.users.index')
    {
        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $message])
            : redirect()->route($redirectRoute)->with('success', $message);
    }

    /**
     * Handle error responses.
     */
    private function handleError($message, $request)
    {
        return $request->expectsJson()
            ? response()->json(['success' => false, 'error' => $message], 500)
            : redirect()->back()->with('error', $message)->withInput();
    }
}