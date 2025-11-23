<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SyncCourses::class,
        \App\Console\Commands\MakeTrait::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:sync-all-courses');
        // $schedule->command('certificates:sync')->daily();
        // $schedule->command('sync:retry-failed-syncs')->daily();
        // $schedule->command('sync:forms --incremental')
        // ->everyThirtyMinutes()
        // ->withoutOverlapping();
        
        // $schedule->command('sync:forms --full')
        //     ->dailyAt('00:00')
        //     ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
