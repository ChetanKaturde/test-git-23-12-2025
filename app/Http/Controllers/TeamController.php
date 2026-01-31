<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitation;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    public function index()
    {
        try {
            // Check permission-based access (admin always has access)
            if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
                abort(403, 'You do not have permission to manage team members.');
            }

            $businessId = Auth::user()->business_id;

            $teamMembers = User::where('business_id', $businessId)
                ->where('id', '!=', Auth::id())
                ->where('role', '!=', 'admin')
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
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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

        // Get allowed permissions and filter the request
        $allowedPermissions = $this->getAllowedPermissionsForBusiness();
        $filteredPermissions = array_intersect($request->permissions ?? [], array_keys($allowedPermissions));

        try {
            // Create user directly
            $user = User::create([
                'business_id' => Auth::user()->business_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'plain_password' => $request->password,
                'team_id' => $request->team_id,
                'permissions' => $filteredPermissions,
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
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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

        // Check for related records that would prevent deletion
        $relatedRecords = $this->checkUserHasRelatedRecords($user);

        if (!empty($relatedRecords)) {
            $message = 'This user cannot be deleted because they have existing records. Please deactivate the user instead. Related records: ' . implode(', ', $relatedRecords);
            return back()->withErrors(['error' => $message]);
        }

        $user->delete();

        return back()->with('success', 'Team member removed successfully.');
    }

    /**
     * Check if user has any related records that would prevent deletion
     */
    private function checkUserHasRelatedRecords(User $user)
    {
        $relatedRecords = [];

        try {
            // Check purchase orders (using relationship if available)
            if (method_exists($user, 'purchaseOrders')) {
                if ($user->purchaseOrders()->count() > 0) {
                    $relatedRecords[] = 'Purchase Orders';
                }
            }
        } catch (\Exception $e) {
            // Skip if relationship fails
        }

        // Schema-safe checks - only query tables/columns that exist
        $checks = [
            ['table' => 'payments', 'column' => 'created_by', 'label' => 'Payments'],
            ['table' => 'work_orders', 'column' => 'operator_id', 'label' => 'Work Orders'],
            ['table' => 'invoices', 'column' => 'generated_by', 'label' => 'Invoices'],
            ['table' => 'expenses', 'column' => 'created_by', 'label' => 'Expenses'],
            ['table' => 'barcodes', 'column' => 'generated_by', 'label' => 'Barcodes'],
            ['table' => 'dispatches', 'column' => 'dispatched_by', 'label' => 'Dispatches'],
            ['table' => 'locations', 'column' => 'manager_id', 'label' => 'Locations'],
            ['table' => 'report_logs', 'column' => 'generated_by', 'label' => 'Report Logs'],
            ['table' => 'returns', 'column' => 'returned_by', 'label' => 'Returns'],
            ['table' => 'quality_checks', 'column' => 'checked_by', 'label' => 'Quality Checks'],
            ['table' => 'batches', 'column' => 'received_by', 'label' => 'Batches'],
            ['table' => 'profile_histories', 'column' => 'changed_by', 'label' => 'Profile Changes'],
        ];

        foreach ($checks as $check) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable($check['table'])) {
                    $count = \Illuminate\Support\Facades\DB::table($check['table'])
                        ->where($check['column'], $user->id)
                        ->count();

                    if ($count > 0) {
                        $relatedRecords[] = $check['label'];
                    }

                    // Special case for returns approved_by
                    if ($check['table'] === 'returns' && $check['column'] === 'returned_by') {
                        $approvedCount = \Illuminate\Support\Facades\DB::table('returns')
                            ->where('approved_by', $user->id)
                            ->count();
                        if ($approvedCount > 0 && !in_array('Returns', $relatedRecords)) {
                            $relatedRecords[] = 'Returns';
                        }
                    }

                    // Special case for quality_checks approved_by
                    if ($check['table'] === 'quality_checks' && $check['column'] === 'checked_by') {
                        $approvedCount = \Illuminate\Support\Facades\DB::table('quality_checks')
                            ->where('approved_by', $user->id)
                            ->count();
                        if ($approvedCount > 0 && !in_array('Quality Checks', $relatedRecords)) {
                            $relatedRecords[] = 'Quality Checks';
                        }
                    }
                }
            } catch (\Exception $e) {
                // Skip this check if table/column doesn't exist or query fails
                continue;
            }
        }

        return $relatedRecords;
    }

    public function updateRole(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
            abort(403, 'You do not have permission to manage user status.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage members of your business.');
        }

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Cannot deactivate admin users.']);
        }

        $wasActive = $user->is_active;
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        // If user was deactivated, invalidate their session
        if ($wasActive && !$user->is_active) {
            // Invalidate all sessions for this user
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        }

        return back()->with('success', "User {$status} successfully.");
    }

    public function viewPassword(User $user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
            abort(403, 'You do not have permission to view passwords.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only view passwords of your business members.');
        }

        $password = $user->plain_password ?? 'Password not available';

        return response()->json(['password' => $password]);
    }


    public function managePermissions(User $user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
            abort(403, 'You do not have permission to manage permissions.');
        }

        if ($user->business_id !== Auth::user()->business_id) {
            abort(403, 'You can only manage permissions of your business members.');
        }

        return view('team.permissions', compact('user'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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

        // Get allowed permissions based on subscription
        $allowedPermissions = $this->getAllowedPermissionsForBusiness();

        // Filter out any permissions that are not allowed
        $filteredPermissions = array_intersect($request->permissions ?? [], array_keys($allowedPermissions));

        // Update team and permissions
        $user->update([
            'team_id' => $request->team_id,
            'permissions' => $filteredPermissions,
        ]);

        return back()->with('success', 'Permissions updated successfully for ' . $user->name);
    }

    private function getAllowedPermissionsForBusiness()
    {
        $allPermissions = [
            'customer_management' => [
                'add_customer' => 'Add Customer',
            ],
            'quotation_management' => [
                'create_quote' => 'Create Quote',
                'edit_quote' => 'Edit Quote',
                'convert_quote_to_invoice' => 'Convert Quote to Invoice',
            ],
            'expense_management' => [
                'add_expense' => 'Add Expense',
                'view_expenses' => 'View Expenses',
                'view_payment_receipts' => 'View Payment Receipts',
            ],
            'reports_analytics' => [
                'view_reports' => 'View Reports',
            ],
            'commodity_management' => [
                'manage_commodity' => 'Manage Commodity',
            ],
            'invoice_management' => [
                'manage_invoices' => 'Manage Invoices',
            ],
            'team_management' => [
                'manage_team' => 'Manage Team',
            ],
        ];

        $allowedPermissions = [];
        $subscription = Auth::user()->currentSubscription();
        if ($subscription) {
            foreach ($allPermissions as $feature => $permissions) {
                if ($subscription->isFeatureEnabled($feature)) {
                    $allowedPermissions = array_merge($allowedPermissions, $permissions);
                }
            }
        }

        return $allowedPermissions;
    }

    public function setDefaultPermissions(User $user)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('manage_team')) {
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

    public function performance(Request $request)
    {
        // Managers and admins can view team performance
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Only administrators and managers can view team performance.');
        }

        $businessId = Auth::user()->business_id;
        $subscriptionTier = Auth::user()->business->subscription_tier ?? 'billing_sales';

        $commodityStats = null;
        $selectedFilterType = null;
        $selectedDateValue = null;
        $selectedQuarter = null;

        if ($request->isMethod('post')) {
            $request->validate([
                'filter_type' => 'required|in:day,month,year,quarter',
            ]);

            $filterType = $request->filter_type;
            $dateValue = null;
            $quarter = null;

            if ($filterType === 'day') {
                $request->validate(['date' => 'required|date']);
                $dateValue = $request->date;
            } elseif ($filterType === 'month') {
                $request->validate(['month' => 'required|string|regex:/^\d{4}-\d{2}$/']);
                $dateValue = $request->month;
            } elseif ($filterType === 'year') {
                $request->validate(['year' => 'required|integer|min:2000|max:2100']);
                $dateValue = $request->year;
            } elseif ($filterType === 'quarter') {
                $request->validate([
                    'year' => 'required|integer|min:2000|max:2100',
                    'quarter' => 'required|integer|min:1|max:4'
                ]);
                $dateValue = $request->year;
                $quarter = $request->quarter;
            }

            // Determine date range based on filter type
            switch ($filterType) {
                case 'day':
                    $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateValue)->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateValue)->endOfDay();
                    break;
                case 'month':
                    $startDate = \Carbon\Carbon::createFromFormat('Y-m', $dateValue)->startOfMonth();
                    $endDate = \Carbon\Carbon::createFromFormat('Y-m', $dateValue)->endOfMonth();
                    break;
                case 'year':
                    $startDate = \Carbon\Carbon::createFromFormat('Y', $dateValue)->startOfYear();
                    $endDate = \Carbon\Carbon::createFromFormat('Y', $dateValue)->endOfYear();
                    break;
                case 'quarter':
                    $year = $dateValue;
                    $quarterStart = \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear()->addMonths(($quarter - 1) * 3);
                    $startDate = $quarterStart->startOfMonth();
                    $endDate = $quarterStart->copy()->addMonths(2)->endOfMonth();
                    break;
            }

            // Commodity Performance - based on invoice items
            $commodityData = \App\Models\InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->where('invoices.business_id', $businessId)
                ->whereBetween('invoices.issue_date', [$startDate, $endDate])
                ->selectRaw('invoice_items.description as commodity, SUM(invoice_items.quantity) as total_quantity, SUM(invoice_items.total_price) as total_revenue')
                ->groupBy('invoice_items.description')
                ->having('total_quantity', '>', 0)
                ->orderBy('total_quantity', 'desc')
                ->get();

            // Get all commodities that exist (have been invoiced at some point)
            $allCommodities = \App\Models\InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->where('invoices.business_id', $businessId)
                ->selectRaw('DISTINCT invoice_items.description as commodity')
                ->pluck('commodity');

            // Get commodities with sales in the period
            $sellingCommodities = $commodityData->pluck('commodity');

            // Not selling commodities: exist but no sales in period
            $notSellingCommodities = $allCommodities->diff($sellingCommodities)->map(function($commodity) {
                return (object) [
                    'commodity' => $commodity,
                    'total_quantity' => 0,
                    'total_revenue' => 0.00
                ];
            });

            if ($commodityData->isNotEmpty() || $notSellingCommodities->isNotEmpty()) {
                $commodityStats = [
                    'best_selling' => $commodityData->first(),
                    'least_selling' => $commodityData->last(),
                    'not_selling' => $notSellingCommodities,
                ];
            }

            $selectedFilterType = $filterType;
            $selectedDateValue = $dateValue;
            $selectedQuarter = $quarter;
        }

        return view('team.performance', compact('commodityStats', 'selectedFilterType', 'selectedDateValue', 'selectedQuarter', 'subscriptionTier'));
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