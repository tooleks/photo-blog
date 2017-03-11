<?php

namespace Console;

use Console\Commands\ChangeUserPassword;
use Console\Commands\ConfigApp;
use Console\Commands\CreateAdministratorUser;
use Console\Commands\DeleteNotPublishedPhotosOlderThanWeek;
use Console\Commands\DeleteUnusedDirectoriesWithinPhotoStorage;
use Console\Commands\CreateRoles;
use Console\Commands\GeneratePhotoAvgColors;
use Console\Commands\GeneratePhotoThumbnails;
use Console\Commands\GenerateRestApiDocumentation;
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
        ChangeUserPassword::class,
        ConfigApp::class,
        CreateAdministratorUser::class,
        CreateRoles::class,
        DeleteNotPublishedPhotosOlderThanWeek::class,
        DeleteUnusedDirectoriesWithinPhotoStorage::class,
        GeneratePhotoAvgColors::class,
        GeneratePhotoThumbnails::class,
        GenerateRestApiDocumentation::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('delete:not_published_photos_older_than_week')
            ->dailyAt('00:00');

        $schedule->command('delete:unused_directories_within_photo_storage')
            ->dailyAt('00:10');
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
