<?php

namespace App\Providers;

use App\Core\ExifFetcher\Contracts\ExifFetcherContract;
use App\Core\ExifFetcher\ExifFetcher;
use App\Core\ThumbnailsGenerator\Contracts\ThumbnailsGeneratorContract;
use App\Core\ThumbnailsGenerator\ThumbnailsGenerator;
use Illuminate\Support\Collection;
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
        $this->app->singleton(ThumbnailsGeneratorContract::class, function ($app) {
            return new ThumbnailsGenerator(app('filesystem')->disk(), config('main.photo.thumbnails'));
        });

        $this->app->bind(ExifFetcherContract::class, ExifFetcher::class);
    }
}
