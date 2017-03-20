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
        $this->app->when(\Api\V1\Http\Controllers\PhotosController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\PhotoPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\PublishedPhotosController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\PublishedPhotoPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\SubscriptionController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\SubscriptionPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\TagsController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\TagPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\TokenController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\TokenPresenter::class);

        $this->app->when(\Api\V1\Http\Controllers\UsersController::class)
            ->needs('$presenterClass')
            ->give(\Api\V1\Presenters\UserPresenter::class);

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
