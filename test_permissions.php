<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test permission system
$users = \App\Models\User::with('permissions.module')->get();

echo "=== PERMISSION SYSTEM TEST ===\n\n";

foreach ($users as $user) {
    echo "User: {$user->name} ({$user->role})\n";
    echo "Permissions:\n";
    
    $modules = \App\Models\Module::all();
    foreach ($modules as $module) {
        $canView = $user->canViewModule($module->name);
        $canCreate = $user->canCreateInModule($module->name);
        $canEdit = $user->canEditInModule($module->name);
        $canDelete = $user->canDeleteInModule($module->name);
        
        if ($canView || $canCreate || $canEdit || $canDelete) {
            echo "  - {$module->display_name}: ";
            $perms = [];
            if ($canView) $perms[] = 'View';
            if ($canCreate) $perms[] = 'Create';
            if ($canEdit) $perms[] = 'Edit';
            if ($canDelete) $perms[] = 'Delete';
            echo implode(', ', $perms) . "\n";
        }
    }
    echo "\n";
}

echo "=== TEST COMPLETED ===\n";