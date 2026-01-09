@extends('layouts.app')

@section('title', 'Manage Permissions')
@section('page-title', 'Manage Permissions')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manage Permissions</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a> 
                    > <a href="{{ route('team.index') }}" class="hover:text-blue-600 transition-colors">Team</a>
                    > <span class="font-medium">{{ $user->name }}</span>
                </nav>
                <div class="flex items-center mt-2 space-x-3">
                    <span class="text-lg font-medium text-gray-700">{{ $user->name }}</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $user->getTeamDisplayName() }}
                    </span>
                </div>
            </div>
            <a href="{{ route('team.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Team
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('team.set-default-permissions', $user) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                        <i class="fas fa-user-cog mr-2"></i>
                        Role Defaults
                    </button>
                </form>
                <form method="POST" action="{{ route('team.grant-full-access', $user) }}" class="inline" onsubmit="return confirm('Grant full access to all modules?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                        <i class="fas fa-unlock mr-2"></i>
                        Full Access
                    </button>
                </form>
                <form method="POST" action="{{ route('team.revoke-all-access', $user) }}" class="inline" onsubmit="return confirm('Revoke all access? User will not be able to access any modules.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors inline-flex items-center">
                        <i class="fas fa-lock mr-2"></i>
                        Revoke All
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Permissions Form -->
    <form method="POST" action="{{ route('team.update-permissions', $user) }}">
        @csrf
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Module Permissions</h3>
                <p class="text-sm text-gray-600 mt-1">Configure what this user can access and modify in each module.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">View</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Create</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($modules as $module)
                            @php
                                $permission = $userPermissions->get($module->id);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $module->display_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $module->description }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="permissions[{{ $module->id }}][can_view]" 
                                           {{ $permission && $permission->can_view ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="permissions[{{ $module->id }}][can_create]" 
                                           {{ $permission && $permission->can_create ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="permissions[{{ $module->id }}][can_edit]" 
                                           {{ $permission && $permission->can_edit ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="permissions[{{ $module->id }}][can_delete]" 
                                           {{ $permission && $permission->can_delete ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('team.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Save Permissions
                </button>
            </div>
        </div>
    </form>

    <!-- Permission Guide -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-blue-900 font-semibold mb-2">Permission Levels</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-blue-800 text-sm">
                    <div><strong>View:</strong> Can see and read data in the module</div>
                    <div><strong>Create:</strong> Can add new records (requires View)</div>
                    <div><strong>Edit:</strong> Can modify existing records (requires View)</div>
                    <div><strong>Delete:</strong> Can remove records (requires View)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add bulk select functionality
    const table = document.querySelector('table');
    if (table) {
        // Add header checkboxes for bulk operations
        const headerRow = table.querySelector('thead tr');
        const viewHeader = headerRow.children[1];
        const createHeader = headerRow.children[2];
        const editHeader = headerRow.children[3];
        const deleteHeader = headerRow.children[4];
        
        // Add "Select All" checkboxes to headers
        [viewHeader, createHeader, editHeader, deleteHeader].forEach((header, index) => {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'h-3 w-3 text-blue-600 border-gray-300 rounded focus:ring-blue-500 bulk-select';
            checkbox.dataset.column = index;
            checkbox.title = 'Select all for this permission';
            
            checkbox.addEventListener('change', function() {
                const columnIndex = parseInt(this.dataset.column) + 1; // +1 because first column is module name
                const columnCheckboxes = table.querySelectorAll(`tbody tr td:nth-child(${columnIndex + 1}) input[type="checkbox"]`);
                columnCheckboxes.forEach(cb => cb.checked = this.checked);
            });
            
            const div = document.createElement('div');
            div.className = 'flex items-center justify-center mt-1';
            div.appendChild(checkbox);
            header.appendChild(div);
        });
        
        // Add row-level "Select All" functionality
        const tbody = table.querySelector('tbody');
        tbody.querySelectorAll('tr').forEach(row => {
            const moduleCell = row.querySelector('td:first-child');
            const checkboxes = row.querySelectorAll('input[type="checkbox"]');
            
            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.className = 'text-xs text-blue-600 hover:text-blue-800 ml-2';
            selectAllBtn.innerHTML = '<i class="fas fa-check-square"></i>';
            selectAllBtn.title = 'Select all permissions for this module';
            
            selectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
            });
            
            const clearAllBtn = document.createElement('button');
            clearAllBtn.type = 'button';
            clearAllBtn.className = 'text-xs text-red-600 hover:text-red-800 ml-1';
            clearAllBtn.innerHTML = '<i class="fas fa-times-square"></i>';
            clearAllBtn.title = 'Clear all permissions for this module';
            
            clearAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
            });
            
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'mt-1';
            buttonContainer.appendChild(selectAllBtn);
            buttonContainer.appendChild(clearAllBtn);
            moduleCell.appendChild(buttonContainer);
        });
    }
    
    // Auto-save functionality (optional)
    let saveTimeout;
    const form = document.querySelector('form');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Clear existing timeout
            if (saveTimeout) {
                clearTimeout(saveTimeout);
            }
            
            // Show saving indicator
            showSavingIndicator();
            
            // Set new timeout for auto-save (2 seconds after last change)
            saveTimeout = setTimeout(() => {
                // Auto-save could be implemented here
                // For now, just hide the saving indicator
                hideSavingIndicator();
            }, 2000);
        });
    });
    
    function showSavingIndicator() {
        let indicator = document.getElementById('saving-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'saving-indicator';
            indicator.className = 'fixed top-4 right-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-2 rounded-md text-sm';
            indicator.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Changes detected...';
            document.body.appendChild(indicator);
        }
        indicator.style.display = 'block';
    }
    
    function hideSavingIndicator() {
        const indicator = document.getElementById('saving-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }
});
</script>
@endsection