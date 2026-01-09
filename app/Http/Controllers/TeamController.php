<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitation;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    public function index()
    {
        try {
            // Check permission-based access (admin always has access)
            if (!Auth::user()->isAdmin() && !Auth::user()->can_manage_team) {
                abort(403, 'You do not have permission to manage team members.');
            }

            $businessId = Auth::user()->business_id;
            
            $teamMembers = User::where('business_id', $businessId)
                ->where('id', '!=', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            // Try to get invitations, but handle if table doesn't exist
            try {
                $pendingInvitations = Invitation::where('business_id', $businessId)
                    ->where('expires_at', '>', now())
                    ->orderBy('created_at', 'desc')
                    ->get();
            } catch (\Exception $e) {
                $pendingInvitations = collect([]);
            }

            return view('settings.team', compact('teamMembers', 'pendingInvitations'));
        } catch (\Exception $e) {
            Log::error('Team index error: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    public function invite(Request $request)
    {
        // Only users with team management permission can invite (admin always can)
        if (!Auth::user()->isAdmin() && !Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to invite team members.');
        }
        
        $business = Auth::user()->business;
        
        // Check Free Plan limits
        if (!$business->canInviteUser()) {
            Log::info('Free user hit team member limit', ['business_id' => $business->id]);
            return response()->json(['message' => 'Free Plan allows only 2 team members. Please upgrade to invite more users.'], 400);
        }

        // Define allowed roles based on subscription tier
        $allowedRoles = $business->subscription_tier === 'billing_sales' 
            ? ['manager', 'viewer']
            : ['manager', 'inventory_manager', 'purchase_team', 'operator', 'viewer'];

        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        try {
            // Create user directly
            $user = User::create([
                'business_id' => Auth::user()->business_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'team_id' => $request->team_id,
                'permissions' => $request->permissions ?? [],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return response()->json([
                'message' => 'Team member added successfully! Login credentials: Email: ' . $request->email . ', Password: ' . $request->password
            ]);
        } catch (\Exception $e) {
            Log::error('Team member creation error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create team member.'], 500);
        }
    }

    public function removeInvitation(Invitation $invitation)
    {
        // Only users with team management permission can remove invitations (admin always can)
        if (!Auth::user()->isAdmin() && !Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to manage invitations.');
        }

        // Ensure invitation belongs to current business
        if ($invitation->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage invitations for your business.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitation removed successfully.');
    }

    public function removeMember(User $user)
    {
        // Only users with team management permission can remove members (admin always can)
        if (!Auth::user()->isAdmin() && !Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to remove team members.');
        }

        // Ensure user belongs to current business
        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage members of your business.');
        }

        // Cannot remove admin users
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Cannot remove administrator users.']);
        }

        $user->delete();

        return back()->with('success', 'Team member removed successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to update roles.');
        }

        $request->validate([
            'role' => 'required|in:manager,inventory_manager,purchase_team,operator,viewer'
        ]);

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage members of your business.');
        }

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Cannot change admin user roles.']);
        }

        // Update the role and set default permissions
        $defaults = $this->getDefaultPermissionsByRole($request->role);
        $defaults['role'] = $request->role;
        $user->update($defaults);

        return back()->with('success', 'User role updated successfully and permissions reset to defaults.');
    }

    public function toggleStatus(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to manage user status.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage members of your business.');
        }

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Cannot deactivate admin users.']);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User {$status} successfully.");
    }

    public function resetPassword(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to reset passwords.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage members of your business.');
        }

        $newPassword = \Illuminate\Support\Str::random(8);
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($newPassword)]);

        return back()->with('success', "Password reset successfully. New password: {$newPassword}");
    }

    public function viewActivities(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to view user activities.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only view activities of your business members.');
        }

        try {
            // Check if activity_log table exists
            if (\Illuminate\Support\Facades\Schema::hasTable('activity_log')) {
                $activities = \App\Models\ActivityLog::where('user_id', $user->id)
                    ->latest()
                    ->paginate(20);
            } else {
                // If activity log table doesn't exist, create empty collection
                $activities = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]), 0, 20, 1, ['path' => request()->url()]
                );
            }
        } catch (\Exception $e) {
            // If activity log fails, create empty collection
            $activities = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 0, 20, 1, ['path' => request()->url()]
            );
        }

        return view('team.activities', compact('user', 'activities'));
    }

    public function managePermissions(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to manage permissions.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage permissions of your business members.');
        }

        return view('team.permissions', compact('user'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to update permissions.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage permissions of your business members.');
        }

        $request->validate([
            'team_id' => ['nullable', 'exists:teams,id'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        // Update team and permissions
        $user->update([
            'team_id' => $request->team_id,
            'permissions' => $request->permissions ?? [],
        ]);

        return back()->with('success', 'Permissions updated successfully for ' . $user->name);
    }

    public function setDefaultPermissions(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to set permissions.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage permissions of your business members.');
        }

        // Set default permissions based on role
        $defaults = $this->getDefaultPermissionsByRole($user->role);
        $user->update($defaults);

        return back()->with('success', 'Default permissions set for ' . $user->getTeamDisplayName());
    }
    
    public function grantFullAccess(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to grant full access.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage permissions of your business members.');
        }

        $user->update([
            'can_manage_materials' => true,
            'can_create_purchase_orders' => true,
            'can_manage_machines' => true,
            'can_create_work_orders' => true,
            'can_manage_invoices' => true,
            'can_manage_vendors' => true,
            'can_manage_team' => false, // Don't grant team management automatically
        ]);

        return back()->with('success', 'Full access granted to ' . $user->name);
    }
    
    public function revokeAllAccess(User $user)
    {
        if (!Auth::user()->can_manage_team) {
            abort(403, 'You do not have permission to revoke access.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage permissions of your business members.');
        }

        $user->update([
            'can_manage_materials' => false,
            'can_create_purchase_orders' => false,
            'can_manage_machines' => false,
            'can_create_work_orders' => false,
            'can_manage_invoices' => false,
            'can_manage_vendors' => false,
            'can_manage_team' => false,
        ]);

        return back()->with('success', 'All access revoked for ' . $user->name);
    }

    public function performance()
    {
        // Managers and admins can view team performance
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Only administrators and managers can view team performance.');
        }

        $businessId = Auth::user()->business_id;
        $subscriptionTier = Auth::user()->business->subscription_tier ?? 'billing_sales';
        
        // Team statistics
        $teamStats = [
            'active_members' => User::where('business_id', $businessId)->where('is_active', true)->count(),
        ];
        
        // Add production stats only for full_erp tier
        if ($subscriptionTier === 'full_erp') {
            $teamStats['completed_work_orders'] = \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'completed')->count();
            $teamStats['pending_work_orders'] = \App\Models\WorkOrder::where('business_id', $businessId)->where('status', 'pending')->count();
            $teamStats['active_machines'] = \App\Models\Machine::where('business_id', $businessId)->where('status', 'available')->count();
        }

        // Team members with work order counts (only for full_erp)
        if ($subscriptionTier === 'full_erp') {
            $teamMembers = User::where('business_id', $businessId)
                ->withCount(['assignedWorkOrders as completed_work_orders' => function($query) {
                    $query->where('status', 'completed');
                }])
                ->get();
        } else {
            $teamMembers = User::where('business_id', $businessId)->get();
        }

        // Recent activities (mock data for now)
        $recentActivities = collect([
            (object)['event' => 'invoice_created', 'description' => 'Invoice INV-2024-001 created', 'created_at' => now()->subHours(2)],
            (object)['event' => 'quotation_sent', 'description' => 'Quotation QUO-2024-002 sent', 'created_at' => now()->subHours(4)],
        ]);
        
        // Add production activities only for full_erp
        if ($subscriptionTier === 'full_erp') {
            $recentActivities->prepend((object)['event' => 'work_order_completed', 'description' => 'Work Order WO-2024-001 completed', 'created_at' => now()->subHours(1)]);
            $recentActivities->prepend((object)['event' => 'machine_maintenance', 'description' => 'Machine M0001 set to maintenance', 'created_at' => now()->subHours(3)]);
        }

        // Recent work orders (only for full_erp)
        $workOrders = collect();
        if ($subscriptionTier === 'full_erp') {
            $workOrders = \App\Models\WorkOrder::where('business_id', $businessId)
                ->with(['assignedTo', 'machine'])
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('team.performance', compact('teamStats', 'teamMembers', 'recentActivities', 'workOrders', 'subscriptionTier'));
    }

    private function getPermissionsByLevel($level)
    {
        switch($level) {
            case 'full': return ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true];
            case 'edit': return ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => false];
            case 'view': return ['can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false];
            default: return ['can_view' => false, 'can_create' => false, 'can_edit' => false, 'can_delete' => false];
        }
    }
    
    private function getDefaultPermissionsByRole($role)
    {
        $defaults = [
            'admin' => [
                'can_manage_materials' => true,
                'can_create_purchase_orders' => true,
                'can_manage_machines' => true,
                'can_create_work_orders' => true,
                'can_manage_invoices' => true,
                'can_manage_vendors' => true,
                'can_manage_team' => true,
                'can_manage_quotations' => true,
            ],
            'manager' => [
                'can_manage_materials' => true,
                'can_create_purchase_orders' => true,
                'can_manage_machines' => true,
                'can_create_work_orders' => true,
                'can_manage_invoices' => true,
                'can_manage_vendors' => true,
                'can_manage_team' => false,
                'can_manage_quotations' => true,
            ],
            'operator' => [
                'can_manage_materials' => false,
                'can_create_purchase_orders' => false,
                'can_manage_machines' => false,
                'can_create_work_orders' => true,
                'can_manage_invoices' => false,
                'can_manage_vendors' => false,
                'can_manage_team' => false,
            ],
        ];

        return $defaults[$role] ?? $defaults['operator'];
    }
}