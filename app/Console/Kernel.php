<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * Here you register your custom commands.
     */
    protected $commands = [
        \App\Console\Commands\CleanupNotifications::class,
          \App\Console\Commands\CleanOrphanNotifications::class,

    ];


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
  
 protected function schedule(Schedule $schedule): void
{
    // Run notification cleanup daily at 2 AM
    $schedule->command('notifications:cleanup')->dailyAt('02:00');

    // Call controller method daily
    $schedule->call(function () {
        app(\App\Http\Controllers\NotificationController::class)->cleanupDeletedOrderNotifications();
    })->daily();
}

  
}
