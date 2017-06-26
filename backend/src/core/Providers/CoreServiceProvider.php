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
        $this->app->singleton(
            \Core\DataProviders\Photo\Contracts\PhotoDataProvider::class,
            \Core\DataProviders\Photo\PhotoDataProvider::class
        );

        $this->app->singleton(
            \Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider::class,
            \Core\DataProviders\Subscription\SubscriptionDataProvider::class
        );

        $this->app->singleton(
            \Core\DataProviders\Tag\Contracts\TagDataProvider::class,
            \Core\DataProviders\Tag\TagDataProvider::class
        );

        $this->app->singleton(
            \Core\DataProviders\User\Contracts\UserDataProvider::class,
            \Core\DataProviders\User\UserDataProvider::class
        );

        $this->app->singleton(
            \Core\Managers\Photo\Contracts\PhotoManager::class,
            \Core\Managers\Photo\PhotoManager::class
        );

        $this->app->singleton(
            \Core\Managers\Subscription\Contracts\SubscriptionManager::class,
            \Core\Managers\Subscription\SubscriptionManager::class
        );

        $this->app->singleton(
            \Core\Managers\Tag\Contracts\TagManager::class,
            \Core\Managers\Tag\TagManager::class
        );

        $this->app->bind(
            \Core\Services\Photo\Contracts\ExifFetcherService::class,
            \Core\Services\Photo\ExifFetcherService::class
        );

        $this->app->bind(
            \Core\Services\Photo\Contracts\ThumbnailsGeneratorService::class,
            \Core\Services\Photo\ThumbnailsGeneratorService::class
        );

        $this->app->singleton(
            \Core\Services\Trash\Contracts\TrashService::class,
            function ($app) {
                return new \Core\Services\Trash\TrashService($app->make('filesystem'), config('main.storage.path.trash'));
            }
        );

        $this->app->singleton(
            \Core\Services\SiteMap\Contracts\SiteMapBuilderService::class,
            \Core\Services\SiteMap\SiteMapBuilderService::class
        );

        $this->app->singleton(
            \Core\Services\Rss\Contracts\RssBuilderService::class,
            \Core\Services\Rss\RssBuilderService::class
        );

        $this->app->bind(
            \Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker::class,
            \Tooleks\Php\AvgColorPicker\Gd\AvgColorPicker::class
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
