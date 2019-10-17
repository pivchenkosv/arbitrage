<?php

namespace App\Console;

use App\Jobs\DeleteLegacyOrders;
use App\Jobs\SyncBitmexOrders;
use App\Jobs\SyncExmoOrders;
use App\Jobs\SyncOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new DeleteLegacyOrders(), 'redis')->hourly();
        $schedule->job(new SyncOrders('Exmo'), 'redis')->hourly();
        $schedule->job(new SyncOrders('Bitmex'), 'redis')->hourly();
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
