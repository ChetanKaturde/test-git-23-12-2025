<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Log slow queries (>100ms)
        DB::listen(function ($query) {
            if ($query->time > 100) {
                Log::warning('Slow Query Detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                    'connection' => $query->connectionName
                ]);
            }
        });

        // Enable query logging in debug mode
        if (config('app.debug')) {
            DB::enableQueryLog();
        }
    }
}