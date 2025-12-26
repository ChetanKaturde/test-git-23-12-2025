@extends('layouts.app')

@section('title', 'Team Management')
@section('page-title', 'Team Management')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ showInviteModal: false }">
    @php
        $business = auth()->user()->business;
        $canInviteUser = $business->canInviteUser();
        $userCount = $business->getActiveUserCount();
    @endphp
    
    <!-- Free Plan Limit Banner -->
    @if($business->subscription_plan === 'free' && !$canInviteUser)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                <div>
                    <p class="text-blue-800 font-medium">Free Plan allows 2 team members.</p>
                    <p class="text-blue-700 text-sm">Current usage: {{ $userCount }}/2 team members</p>
                </div>
            </div>
            <a href="/pricing" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                Upgrade to add more
            </a>
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
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" title="Upgrade to add more team members">
                    <i class="fas fa-plus mr-2"></i>
                    Add Team Member
                </button>
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
                                 class="absolute z-50 mt-8 w-80 bg-white rounded-lg shadow-lg border border-gray-200 p-4">
                                <h4 class="font-medium text-gray-900 mb-3">{{ $member->name }} - Permissions</h4>
                                <form method="POST" action="{{ route('team.update-permissions', $member) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Materials</label>
                                            <input type="checkbox" name="can_manage_materials" value="1" 
                                                   {{ $member->can_manage_materials ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Purchase Orders</label>
                                            <input type="checkbox" name="can_create_purchase_orders" value="1" 
                                                   {{ $member->can_create_purchase_orders ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Machines</label>
                                            <input type="checkbox" name="can_manage_machines" value="1" 
                                                   {{ $member->can_manage_machines ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Work Orders</label>
                                            <input type="checkbox" name="can_create_work_orders" value="1" 
                                                   {{ $member->can_create_work_orders ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Invoices</label>
                                            <input type="checkbox" name="can_manage_invoices" value="1" 
                                                   {{ $member->can_manage_invoices ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Vendors</label>
                                            <input type="checkbox" name="can_manage_vendors" value="1" 
                                                   {{ $member->can_manage_vendors ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-700">Team Management</label>
                                            <input type="checkbox" name="can_manage_team" value="1" 
                                                   {{ $member->can_manage_team ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
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
                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('team.toggle-status', $member) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $member->is_active ? 'text-orange-600 hover:text-orange-800' : 'text-green-600 hover:text-green-800' }}" 
                                        title="{{ $member->is_active ? 'Deactivate' : 'Activate' }} user">
                                    <i class="fas {{ $member->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                </button>
                            </form>
                            
                            <!-- Reset Password -->
                            <form method="POST" action="{{ route('team.reset-password', $member) }}" 
                                  onsubmit="return confirm('Are you sure you want to reset this user\'s password?')" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800" title="Reset password">
                                    <i class="fas fa-key text-sm"></i>
                                </button>
                            </form>
                            
                            <!-- View Activities -->
                            <a href="{{ route('team.view-activities', $member) }}" class="text-purple-600 hover:text-purple-800" title="View activities">
                                <i class="fas fa-history text-sm"></i>
                            </a>
                            
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
                            {{ $invitation->getRoleDisplayName() }}
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
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
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
                    <div class="space-y-4">
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
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" 
                                    id="role" 
                                    x-model="form.role"
                                    @change="updateRoleDescription()"
                                    required
                                    aria-describedby="role-help role-error"
                                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="errors.role ? 'border-red-300' : 'border-gray-300'">
                                <option value="">Choose a role for this team member</option>
                                @if(auth()->user()->business->subscription_tier === 'billing_sales')
                                    <option value="manager">Manager - Full access to sales & billing</option>
                                    <option value="viewer">Viewer - Read-only access to reports</option>
                                @else
                                    <option value="manager">Manager - Full access to all modules</option>
                                    <option value="inventory_manager">Inventory Manager - Manage stock and materials</option>
                                    <option value="purchase_team">Purchase Team - Handle orders and vendors</option>
                                    <option value="operator">Machine Operator - View tasks and update status</option>
                                    <option value="viewer">Viewer - Read-only access to reports</option>
                                @endif
                            </select>
                            <p x-show="roleDescription" x-text="roleDescription" id="role-help" class="mt-1 text-xs text-blue-600"></p>
                            <p x-show="errors.role" x-text="errors.role" id="role-error" class="mt-1 text-xs text-red-600"></p>
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
            role: ''
        },
        errors: {},
        message: '',
        messageType: 'success',
        loading: false,
        roleDescription: '',
        
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
        
        updateRoleDescription() {
            const tier = '{{ auth()->user()->business->subscription_tier }}';
            
            const descriptions = tier === 'billing_sales' ? {
                'manager': 'Can manage customers, quotations, invoices, and team members',
                'viewer': 'Can view reports and data but cannot make changes'
            } : {
                'manager': 'Can access all features and manage team members',
                'inventory_manager': 'Can manage inventory, materials, and stock levels',
                'purchase_team': 'Can create and manage purchase orders and vendor relationships',
                'operator': 'Can view assigned tasks and update work order status',
                'viewer': 'Can view reports and data but cannot make changes'
            };
            
            this.roleDescription = descriptions[this.form.role] || '';
            
            if (!this.form.role) {
                this.errors.role = 'Please select a role';
            } else {
                delete this.errors.role;
            }
        },
        
        async submitForm() {
            this.validateEmail();
            this.updateRoleDescription();
            
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
                formData.append('role', this.form.role);
                
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
                    this.form = { email: '', name: '', password: '', role: '' };
                    this.roleDescription = '';
                    
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