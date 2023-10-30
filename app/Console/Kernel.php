<?php

namespace App\Console;

use App\Jobs\BuildChangelog;
use App\Jobs\GenerateGlobalPlayerStats;
use App\Jobs\ClearOldAudio;
use App\Jobs\ClearOldDectalks;
use App\Jobs\GenerateNumbersStationPass;
use App\Jobs\GetPlayerCounts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

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
        $schedule->job(new BuildChangelog)->everyFiveMinutes();
        $schedule->job(new GetPlayerCounts)->everyFiveMinutes();
        $schedule->job(new GenerateNumbersStationPass)->hourly();
        $schedule->job(new ClearOldDectalks)->dailyAt('03:03');
        $schedule->job(new ClearOldAudio)->dailyAt('03:07');
        $schedule->job(new GenerateGlobalPlayerStats)->daily();

        if (App::environment('local')) {
            $schedule->command('telescope:prune')->daily();
        }
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
