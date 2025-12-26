<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class WarehousePolicy
{
    use HandlesAuthorization;

    /**
     * Check if user has a specific permission on Warehouse Management module,
     */
    protected function hasPermission(User $user, string $permission): bool
    {
        // ✅ Allow full access for admin-type roles
        if (in_array($user->role, ['admin', 'inventory_manager', 'purchase_team','user'])) {
            return true;
        }

        // ✅ If 'user' role → allow only can_view
        if ($user->role === 'user' && $permission === 'can_view') {
            return true;
        }

        // ✅ Check permission via assigned permissions
        Log::debug("Checking permission '{$permission}' for User #{$user->id}");

        $hasPermission = $user->permissions()
            ->whereHas('module', function ($q) {
                $q->where('name', 'Warehouse Management')->where('is_active', true);
            })
            ->where($permission, true)
            ->exists();

        Log::debug("Permission '{$permission}' granted? " . ($hasPermission ? 'Yes' : 'No'));

        return $hasPermission;
    }

    public function viewAny(User $user)
    {
        return $this->hasPermission($user, 'can_view');
    }

    public function view(User $user, Warehouse $warehouse)
    {
        return $this->hasPermission($user, 'can_view');
    }

    public function create(User $user)
    {
        return $this->hasPermission($user, 'can_create');
    }

    public function update(User $user, Warehouse $warehouse)
    {
        return $this->hasPermission($user, 'can_edit');
    }

    public function delete(User $user, Warehouse $warehouse)
    {
        return $this->hasPermission($user, 'can_delete');
    }
}
