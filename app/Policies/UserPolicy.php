<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'inventory']) && $user->is_active;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model)
    {
        return in_array($user->role, ['admin', 'inventory']) && $user->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'inventory']) && $user->is_active;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        // Admin can update any user
        if ($user->role === 'admin' && $user->is_active) {
            return true;
        }

        // Inventory managers can update non-admin users
        if ($user->role === 'inventory' && $user->is_active && $model->role !== 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        // Admin can delete any user except themselves
        if ($user->role === 'admin' && $user->is_active && $user->id !== $model->id) {
            return true;
        }

        // Inventory managers can delete non-admin users
        if ($user->role === 'inventory' && $user->is_active && $model->role !== 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        return $user->role === 'admin' && $user->is_active;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        return $user->role === 'admin' && $user->is_active;
    }

    /**
     * Determine whether the user can manage permissions.
     */
    public function managePermissions(User $user, User $model)
    {
        // Admin can manage permissions for any user
        if ($user->role === 'admin' && $user->is_active) {
            return true;
        }

        // Inventory managers can manage permissions for non-admin users
        if ($user->role === 'inventory' && $user->is_active && $model->role !== 'admin') {
            return true;
        }

        return false;
    }
}