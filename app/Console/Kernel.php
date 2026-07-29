<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->command('booking_reminder:cron')->everyMinute()->appendOutputTo(base_path('cronlog.log'));
        $schedule->command('booking_auto_complete:cron')->everyMinute()->appendOutputTo(base_path('cronlog.log'));
        $schedule->command('points_expire:cron')->everyMinute()->appendOutputTo(base_path('cronlog.log'));
        $schedule->command('mlm_monthly_recharge:cron')->everyMinute()->appendOutputTo(base_path('cronlog.log'));
        $schedule->command('acceptance_rating_reset:cron')->everyMinute()->appendOutputTo(base_path('cronlog.log'));
        $schedule->command('cron_stylist_booking_no_response:cron')->everyMinute()->appendOutputTo(base_path('cronlog.log'));
        $schedule->command('cron_document_expire_notification:cron')->appendOutputTo(base_path('cronlog.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
