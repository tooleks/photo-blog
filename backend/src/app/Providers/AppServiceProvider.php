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
            return new \Lib\ThumbnailsGenerator\ThumbnailsGenerator(config('main.photo.thumbnails'));
        });

        $this->app->bind(
            \Lib\SiteMap\Contracts\Builder::class,
            \Lib\SiteMap\Builder::class
        );

        $this->app->bind(
            \Lib\Rss\Contracts\Builder::class,
            \Lib\Rss\Builder::class
        );
    }
}
