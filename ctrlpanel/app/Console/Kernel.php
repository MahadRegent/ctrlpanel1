<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ChargeServers::class,
        Commands\DeleteExpiredCoupons::class,
        Commands\RefreshServerCache::class,
        Commands\SyncNodeResources::class,
        Commands\SyncServerInfo::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('servers:charge')->everyMinute();
        $schedule->command('cp:versioncheck:get')->daily();
        $schedule->command('payments:open:clear')->daily();
        $schedule->command('coupons:delete')->daily();
        
        // Sync node resources every 5 minutes for optimal performance
        $schedule->command('nodes:sync-resources')->everyFiveMinutes();
        
        // Sync server info every 15 minutes (less critical than node resources)
        $schedule->command('servers:sync-info')->everyFifteenMinutes();

        //log cronjob activity
        $schedule->call(function () {
            Storage::disk('logs')->put('cron.log', 'Last activity from cronjobs - ' . now());
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
