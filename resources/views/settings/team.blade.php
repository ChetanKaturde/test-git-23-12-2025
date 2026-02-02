@extends('layouts.app')

@section('title', 'Team Management')
@section('page-title', 'Team Management')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ showInviteModal: false }">
    @php
        $business = auth()->user()->business;
        $canInviteUser = $business->canInviteUser();
        $userCount = $business->users()->count();
        $allowedUsers = $business->getAllowedUsers();
        $hasReachedLimit = $business->hasReachedUserLimit();
    @endphp
    
    <!-- Team Member Limit Reached Banner -->
    @if($hasReachedLimit && $allowedUsers)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-amber-600 mr-3"></i>
                <div>
                    <p class="text-amber-800 font-medium">You have reached your team member limit.</p>
                    <p class="text-amber-700 text-sm">Current usage: {{ $userCount }}/{{ $allowedUsers }} users (includes business owner)</p>
                </div>
            </div>
            <a href="/pricing" style="background-color: #d97706; color: white;" class="px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium">
                <i class="fas fa-arrow-up mr-2"></i>
                Update Plan
            </a>
            
            {{-- <a href="/pricing" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium">
                <i class="fas fa-arrow-up mr-2"></i>
                Update Plan
            </a> --}}
        </div>
    </div>
    @endif
    
    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header with Invite Button -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                <p class="text-sm text-gray-600 mt-1">Manage your workshop team and invite new members</p>
            </div>
            @if($canInviteUser)
                <x-button @click="showInviteModal = true" variant="primary">
                    <i class="fas fa-plus mr-2"></i>
                    Add Team Member
                </x-button>
            @else
                @if($allowedUsers)
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" title="You have reached your limit of {{ $allowedUsers }} users. Please upgrade your plan to add more team members.">
                    <i class="fas fa-plus mr-2"></i>
                    Add Team Member
                </button>
                @else
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" title="Upgrade to add more team members">
                    <i class="fas fa-plus mr-2"></i>
                    Add Team Member
                </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Current Team Members -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900">Active Team Members</h4>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($teamMembers as $member)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Permission Toggles -->
                        <div class="flex flex-wrap gap-2" x-data="{ showPermissions: false }">
                            <button @click="showPermissions = !showPermissions" 
                                    class="text-xs px-2 py-1 border border-gray-300 rounded bg-blue-50 text-blue-800 hover:bg-blue-100">
                                <i class="fas fa-cog mr-1"></i> Permissions
                            </button>
                            
                            <!-- Permission Modal -->
                            <div x-show="showPermissions" @click.away="showPermissions = false"
                                 class="absolute z-50 mt-8 w-96 bg-white rounded-lg shadow-lg border border-gray-200 p-4">
                                <h4 class="font-medium text-gray-900 mb-3">{{ $member->name }} - Permissions & Team</h4>
                                <form method="POST" action="{{ route('team.update-permissions', $member) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="space-y-3">
                                        <!-- Team Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Team</label>
                                            <select name="team_id" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">No Team</option>
                                                @foreach(\App\Models\Team::where('business_id', auth()->user()->business_id)->get() as $team)
                                                    <option value="{{ $team->id }}" {{ $member->team_id == $team->id ? 'selected' : '' }}>
                                                        {{ $team->team_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Permissions Checkboxes -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                @php
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

                                                    $availablePermissions = [];
                                                    $subscription = auth()->user()->currentSubscription();
                                                    if ($subscription) {
                                                        foreach ($allPermissions as $feature => $permissions) {
                                                            if ($subscription->isFeatureEnabled($feature)) {
                                                                $availablePermissions = array_merge($availablePermissions, $permissions);
                                                            }
                                                        }
                                                    }

                                                    $userPermissions = $member->permissions ?? [];
                                                @endphp
                                                @foreach($availablePermissions as $key => $label)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                                               {{ in_array($key, $userPermissions) ? 'checked' : '' }}
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <label class="ml-2 text-xs text-gray-700">{{ $label }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button type="button" @click="showPermissions = false"
                                                class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800">Cancel</button>
                                        <button type="submit"
                                                class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <!-- Toggle Status Switch -->
                            <form method="POST" action="{{ route('team.toggle-status', $member) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="toggle-switch {{ $member->is_active ? 'active' : '' }}" title="{{ $member->is_active ? 'Deactivate user' : 'Activate user' }}">
                                    <span class="toggle-slider"></span>
                                </button>
                            </form>

                            <style>
                            .toggle-switch {
                                position: relative;
                                width: 44px;
                                height: 24px;
                                background-color: #ccc;
                                border-radius: 24px;
                                border: none;
                                cursor: pointer;
                                transition: background-color 0.3s ease;
                                outline: none;
                            }
                            .toggle-switch:hover {
                                background-color: #bbb;
                            }
                            .toggle-switch.active {
                                background-color: #10b981;
                            }
                            .toggle-switch.active:hover {
                                background-color: #059669;
                            }
                            .toggle-slider {
                                position: absolute;
                                top: 2px;
                                left: 2px;
                                width: 20px;
                                height: 20px;
                                background-color: white;
                                border-radius: 50%;
                                transition: transform 0.3s ease;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            }
                            .toggle-switch.active .toggle-slider {
                                transform: translateX(20px);
                            }
                            </style>

                            <!-- View Password -->
                            <button onclick="viewPassword({{ $member->id }}, '{{ $member->name }}')"
                                    class="text-blue-600 hover:text-blue-800" title="View password">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <!-- Permissions are managed via the inline modal above -->

                            <!-- Remove User -->
                            <form method="POST" action="{{ route('team.remove-member', $member) }}"
                                  onsubmit="return confirm('Are you sure you want to remove this team member?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Remove user">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center">
                    <i class="fas fa-users text-gray-400 text-3xl mb-4"></i>
                    <p class="text-gray-500">No team members yet. Invite your first team member!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pending Invitations -->
    @if($pendingInvitations->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900">Pending Invitations</h4>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($pendingInvitations as $invitation)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $invitation->email }}</div>
                            <div class="text-sm text-gray-500">Invited {{ $invitation->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $invitation->getTeamDisplayName() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Invited
                        </span>
                        <div class="flex items-center space-x-2">
                            <button onclick="copyInviteLink('{{ url('/register?token=' . $invitation->token) }}')" 
                                    class="text-blue-600 hover:text-blue-800" title="Copy invite link">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                            <form method="POST" action="{{ route('team.remove-invitation', $invitation) }}" 
                                  onsubmit="return confirm('Are you sure you want to cancel this invitation?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Cancel invitation">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Invite Modal -->
    <div x-show="showInviteModal" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" role="dialog" aria-labelledby="modal-title" aria-modal="true">
        <div class="relative top-20 mx-auto p-6 border max-w-4xl w-full shadow-lg rounded-md bg-white">
            <div class="mt-3" x-data="inviteForm()">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modal-title" class="text-lg font-medium text-gray-900">Add Team Member</h3>
                    <button @click="showInviteModal = false" class="text-gray-400 hover:text-gray-600" aria-label="Close modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Success/Error Messages -->
                <div x-show="message" x-transition class="mb-4 p-3 rounded-md" :class="messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                    <p x-text="message"></p>
                </div>
                
                <form @submit.prevent="submitForm()" x-ref="inviteForm">
                    @csrf
                    <div class="space-y-6">
                        <!-- Basic Information Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                        name="email"
                                        id="email"
                                        x-model="form.email"
                                        @blur="validateEmail()"
                                        required
                                        aria-describedby="email-help email-error"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="errors.email ? 'border-red-300' : 'border-gray-300'"
                                        placeholder="Enter work email address (e.g., john@company.com)">
                                <p id="email-help" class="mt-1 text-xs text-gray-500">This will be their login username</p>
                                <p x-show="errors.email" x-text="errors.email" id="email-error" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                        name="name"
                                        id="name"
                                        x-model="form.name"
                                        required
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="errors.name ? 'border-red-300' : 'border-gray-300'"
                                        placeholder="Enter full name">
                                <p x-show="errors.name" x-text="errors.name" class="mt-1 text-xs text-red-600"></p>
                            </div>
                        </div>

                        <!-- Password and Team Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                        name="password"
                                        id="password"
                                        x-model="form.password"
                                        required
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="errors.password ? 'border-red-300' : 'border-gray-300'"
                                        placeholder="Set login password (min 8 characters)">
                                <p class="mt-1 text-xs text-gray-500">User can change this password after first login</p>
                                <p x-show="errors.password" x-text="errors.password" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Team (Optional)
                                </label>
                                <select name="team_id"
                                        id="team_id"
                                        x-model="form.team_id"
                                        class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300">
                                    <option value="">No Team</option>
                                    @foreach(\App\Models\Team::where('business_id', auth()->user()->business_id)->get() as $team)
                                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Assign user to a team for identification</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Permissions <span class="text-red-500">*</span>
                            </label>
                            <div class="border border-gray-300 rounded-md p-3 max-h-40 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-2">
                                    @php
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

                                        $availablePermissions = [];
                                        $subscription = auth()->user()->currentSubscription();
                                        if ($subscription) {
                                            foreach ($allPermissions as $feature => $permissions) {
                                                if ($subscription->isFeatureEnabled($feature)) {
                                                    $availablePermissions = array_merge($availablePermissions, $permissions);
                                                }
                                            }
                                        }
                                    @endphp
                                    @foreach($availablePermissions as $key => $label)
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $key }}"
                                               x-model="form.permissions"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label class="ml-2 text-sm text-gray-700">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Select the permissions this user should have</p>
                            <p x-show="errors.permissions" x-text="errors.permissions" class="mt-1 text-xs text-red-600"></p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <x-button type="button" @click="showInviteModal = false" variant="secondary">
                            Cancel
                        </x-button>
                        <x-button type="submit" variant="primary" x-bind:disabled="loading">
                            <span x-show="!loading">Add Team Member</span>
                            <span x-show="loading" class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Adding...
                            </span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyInviteLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        showToast('Invite link copied to clipboard!', 'success');
    }).catch(function() {
        showToast('Failed to copy link', 'error');
    });
}

function viewPassword(userId, userName) {
    fetch(`/settings/team/members/${userId}/view-password`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.password) {
            alert(`Password for ${userName}: ${data.password}`);
        } else {
            showToast('Password not available', 'error');
        }
    })
    .catch(error => {
        showToast('Failed to fetch password', 'error');
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (document.body.contains(toast)) {
            document.body.removeChild(toast);
        }
    }, 3000);
}

function inviteForm() {
    return {
        form: {
            email: '',
            name: '',
            password: '',
            team_id: '',
            permissions: []
        },
        errors: {},
        message: '',
        messageType: 'success',
        loading: false,
        
        validateEmail() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.form.email) {
                this.errors.email = 'Email address is required';
            } else if (!emailRegex.test(this.form.email)) {
                this.errors.email = 'Please enter a valid email address';
            } else {
                delete this.errors.email;
            }
        },
        
        validatePermissions() {
            if (!this.form.permissions || this.form.permissions.length === 0) {
                this.errors.permissions = 'Please select at least one permission';
            } else {
                delete this.errors.permissions;
            }
        },
        
        async submitForm() {
            this.validateEmail();
            this.validatePermissions();

            if (Object.keys(this.errors).length > 0) {
                this.message = 'Please fix the errors above';
                this.messageType = 'error';
                return;
            }

            this.loading = true;
            this.message = '';

            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('email', this.form.email);
                formData.append('name', this.form.name);
                formData.append('password', this.form.password);
                formData.append('team_id', this.form.team_id);

                // Add permissions
                this.form.permissions.forEach(permission => {
                    formData.append('permissions[]', permission);
                });

                const response = await fetch('{{ route("team.invite") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.message = 'Team member added successfully!';
                    this.messageType = 'success';
                    this.form = { email: '', name: '', password: '', team_id: '', permissions: [] };

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    this.message = data.message || 'Failed to send invitation';
                    this.messageType = 'error';
                }
            } catch (error) {
                this.message = 'Network error. Please try again.';
                this.messageType = 'error';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection