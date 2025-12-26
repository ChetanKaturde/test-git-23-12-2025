<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\UserPermission;

class EnsureUserHasPermissions
{
    /**
     * Handle an incoming request.
     * Ensures user has permissions set up, especially after role changes.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }
        
        // Skip for admin users
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Check if user has any permissions set
        $hasPermissions = UserPermission::where('user_id', $user->id)->exists();
        
        if (!$hasPermissions) {
            // Set default permissions based on role
            $this->setDefaultPermissions($user);
        }
        
        return $next($request);
    }
    
    private function setDefaultPermissions($user)
    {
        $defaults = $user->getDefaultPermissions();
        $modules = Module::whereIn('name', array_keys($defaults))->get();

        foreach ($modules as $module) {
            $level = $defaults[$module->name];
            $perms = $this->getPermissionsByLevel($level);
            
            UserPermission::updateOrCreate(
                ['user_id' => $user->id, 'module_id' => $module->id],
                $perms
            );
        }
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
}