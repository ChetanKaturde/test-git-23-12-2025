<?php
namespace App\Policies;

use App\Models\User;
use App\Models\WarehouseBlock;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseBlockPolicy
{
    use HandlesAuthorization;

protected function hasPermission(User $user, string $permission): bool
{
    $moduleName = 'View blocks'; // Can be dynamic if needed

    return $user->permissions()
        ->whereHas('module', function ($q) use ($moduleName) {
            $q->where('name', $moduleName)->where('is_active', 1);
        })
        ->where($permission, 1)
        ->exists();
}

public function view(User $user, WarehouseBlock $block)
{
    return $this->hasPermission($user, 'can_view', 'View blocks');
}

public function update(User $user, WarehouseBlock $block)
{
    return $this->hasPermission($user, 'can_edit', 'View blocks');
}

public function delete(User $user, WarehouseBlock $block)
{
    return $this->hasPermission($user, 'can_delete', 'View blocks');
}


    public function create(User $user)
    {
        return $this->hasPermission($user, 'can_create');
    }

  
    
}