<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
            \Lib\ExifFetcher\ExifFetcher::class);

        $this->app->bind(
            \Core\DAL\Repositories\Contracts\PhotoRepository::class,
            \Core\DAL\Repositories\PhotoRepository::class
        );

        $this->app->bind(
            \Core\DAL\Repositories\Contracts\UserRepository::class,
            \Core\DAL\Repositories\UserRepository::class
        );
    }
}
