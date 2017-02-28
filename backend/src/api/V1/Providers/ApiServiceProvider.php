<?php

namespace Api\V1\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ApiServiceProvider.
 *
 * @package Api\V1\Providers
 */
class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(\Api\V1\Http\Controllers\TokenController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\TokenPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\UserController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\UserPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\PhotoController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\PhotoPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\PublishedPhotoController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\PublishedPhotoPresenter::class);

        $this->app->bind(
            \Core\DataServices\User\Contracts\UserDataService::class,
            \Core\DataServices\User\UserDataService::class
        );

        $this->app->bind(
            \Core\DataServices\Photo\Contracts\PhotoDataService::class,
            \Core\DataServices\Photo\PhotoDataService::class
        );

        $this->app->bind(
            \Lib\AvgColorPicker\Contracts\AvgColorPicker::class,
            \Lib\AvgColorPicker\GD\AvgColorPicker::class
        );

        $this->app->bind(\Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator::class, function () {
            return new \Lib\ThumbnailsGenerator\ThumbnailsGenerator(config('main.photo.thumbnails'));
        });

        $this->app->bind(
            \Lib\ExifFetcher\Contracts\ExifFetcher::class,
            \Lib\ExifFetcher\ExifFetcher::class
        );
    }
}
