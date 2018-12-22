<?php

namespace Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 *
 * @package Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Console\Commands\ChangeUserPassword::class,
        \Console\Commands\CreateAdministratorUser::class,
        \Console\Commands\CreateRoles::class,
        \Console\Commands\DeleteDetachedPhotosOlderThanWeek::class,
        \Console\Commands\DeleteUnusedObjectsFromPhotoStorage::class,
        \Console\Commands\GeneratePhotosMetadata::class,
        \Console\Commands\GenerateRestApiDocumentation::class,
        \Console\Commands\SendWeeklySubscriptionMails::class,
        \Console\Commands\TestScheduler::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(\Console\Commands\TestScheduler::class)
            ->hourly();

        $schedule->command(\Console\Commands\DeleteDetachedPhotosOlderThanWeek::class)
            ->dailyAt('00:00')
            ->onOneServer();

        $schedule->command(\Console\Commands\DeleteUnusedObjectsFromPhotoStorage::class)
            ->dailyAt('00:10')
            ->onOneServer();

        $schedule->command(\Console\Commands\SendWeeklySubscriptionMails::class)
            ->weekly()
            ->sundays()
            ->at('06:00')
            ->onOneServer();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
