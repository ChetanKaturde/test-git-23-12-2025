<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Module;
use App\Models\UserPermission;

class SetupDefaultPermissions extends Command
{
    protected $signature = 'permissions:setup';
    protected $description = 'Setup default permissions for all users based on their roles';

    public function handle()
    {
        $users = User::all();
        $modules = Module::all();

        foreach ($users as $user) {
            $this->info("Setting up permissions for {$user->name} ({$user->role})");
            
            // Clear existing permissions
            $user->permissions()->delete();
            
            $defaultPermissions = $user->getDefaultPermissions();
            
            foreach ($modules as $module) {
                $modulePermission = $defaultPermissions[$module->name] ?? 'none';
                
                if ($modulePermission !== 'none') {
                    $permissions = $this->getPermissionFlags($modulePermission);
                    
                    UserPermission::create([
                        'user_id' => $user->id,
                        'module_id' => $module->id,
                        'can_view' => $permissions['view'],
                        'can_create' => $permissions['create'],
                        'can_edit' => $permissions['edit'],
                        'can_delete' => $permissions['delete'],
                    ]);
                }
            }
        }

        $this->info('Default permissions setup completed!');
    }

    private function getPermissionFlags($level)
    {
        switch ($level) {
            case 'full':
                return ['view' => true, 'create' => true, 'edit' => true, 'delete' => true];
            case 'edit':
                return ['view' => true, 'create' => true, 'edit' => true, 'delete' => false];
            case 'view':
                return ['view' => true, 'create' => false, 'edit' => false, 'delete' => false];
            default:
                return ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
        }
    }
}