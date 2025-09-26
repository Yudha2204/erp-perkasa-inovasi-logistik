<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run exchange revaluation on the last day of each month at 23:59
        $schedule->command('exchange:revaluate')
            ->monthlyOn(null, '23:59')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->runInBackground();

        // Run Profit & Loss closing right after revaluation
        $schedule->command('process:profitloss-close')
            ->monthlyOn(null, '23:59')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->runInBackground();
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