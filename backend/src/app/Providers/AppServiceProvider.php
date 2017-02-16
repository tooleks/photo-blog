<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
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
        $this->app->bind(\Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator::class, function () {
            return new \Lib\ThumbnailsGenerator\ThumbnailsGenerator($this->app->make('filesystem')->disk(), config('main.photo.thumbnails'));
        });

        $this->app->bind(
            \Lib\ExifFetcher\Contracts\ExifFetcher::class,
            \Lib\ExifFetcher\ExifFetcher::class
        );

        $this->app->bind(
            \Core\DAL\DataService\User\Contracts\UserDataService::class,
            \Core\DAL\DataService\User\UserDataService::class
        );

        $this->app->bind(
            \Core\DAL\DataService\Photo\Contracts\PhotoDataService::class,
            \Core\DAL\DataService\Photo\PhotoDataService::class
        );
    }
}
