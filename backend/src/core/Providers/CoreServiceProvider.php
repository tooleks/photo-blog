<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class CoreServiceProvider.
 *
 * @package Core\Providers
 */
class CoreServiceProvider extends ServiceProvider
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
        $this->app->bind(
            \Core\DataProviders\Photo\Contracts\PhotoDataProvider::class,
            \Core\DataProviders\Photo\PhotoDataProvider::class
        );

        $this->app->bind(
            \Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider::class,
            \Core\DataProviders\Subscription\SubscriptionDataProvider::class
        );

        $this->app->bind(
            \Core\DataProviders\Tag\Contracts\TagDataProvider::class,
            \Core\DataProviders\Tag\TagDataProvider::class
        );

        $this->app->bind(
            \Core\DataProviders\User\Contracts\UserDataProvider::class,
            \Core\DataProviders\User\UserDataProvider::class
        );

        $this->app->bind(
            \Core\Services\Photo\Contracts\AvgColorGeneratorService::class,
            \Core\Services\Photo\AvgColorGeneratorService::class
        );

        $this->app->bind(
            \Core\Services\Photo\Contracts\ExifFetcherService::class,
            \Core\Services\Photo\ExifFetcherService::class
        );

        $this->app->bind(
            \Core\Services\Photo\Contracts\FileSaverService::class,
            \Core\Services\Photo\FileSaverService::class
        );

        $this->app->bind(
            \Core\Services\Photo\Contracts\ThumbnailsGeneratorService::class,
            \Core\Services\Photo\ThumbnailsGeneratorService::class
        );

        $this->app->singleton(
            \Core\FileSystem\Trash\Contracts\Trash::class,
            function ($app) {
                return new \Core\FileSystem\Trash\Trash($app->make('filesystem'), config('main.storage.path.trash'));
            }
        );

        $this->app->bind(
            \Core\Services\SiteMap\Contracts\SiteMapBuilderService::class,
            \Core\Services\SiteMap\SiteMapBuilderService::class
        );

        $this->app->bind(
            \Core\Services\Rss\Contracts\RssBuilderService::class,
            \Core\Services\Rss\RssBuilderService::class
        );
    }
}
