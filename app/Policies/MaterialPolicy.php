<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Material;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPolicy
{
    use HandlesAuthorization;

    /**
     * Admin has full access. Others follow permissions.
     */
    protected function hasPermission(User $user, string $permission): bool
    {
        // ✅ Full access for admin
        if ($user->role === 'admin') {
            return true;
        }

        // ✅ Check if user has the specific permission in the active module
        return $user->permissions()
            ->whereHas('module', function ($q) {
                $q->where('name', 'Materials')  // Match exact module name from table
                  ->where('is_active', true);
            })
            ->where($permission, true)
            ->exists();
    }

    public function viewAny(User $user)
    {
        return $this->hasPermission($user, 'can_view');
    }

    public function view(User $user, Material $material)
    {
        return $this->hasPermission($user, 'can_view');
    }

    public function create(User $user)
    {
        return $this->hasPermission($user, 'can_create');
    }

    public function update(User $user, Material $material)
    {
        return $this->hasPermission($user, 'can_edit');
    }

    public function delete(User $user, Material $material)
    {
        return $this->hasPermission($user, 'can_delete');
    }
}
