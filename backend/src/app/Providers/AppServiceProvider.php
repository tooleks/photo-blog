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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPackageServices();

        $this->registerLibServices();

        $this->registerAppServices();

        $this->registerApiV1Services();
    }

    /**
     * Register "api.v1" services.
     *
     * @return void
     */
    protected function registerApiV1Services(): void
    {
        $this->app->bind(
            \Api\V1\Http\Proxy\Contracts\OAuthProxy::class,
            \Api\V1\Http\Proxy\CookieOAuthProxy::class
        );
    }

    /**
     * Register "app" services.
     *
     * @return void
     */
    protected function registerAppServices(): void
    {
        $this->app->bind(
            \App\DataProviders\Photo\Contracts\PhotoDataProvider::class,
            \App\DataProviders\Photo\PhotoDataProvider::class
        );

        $this->app->bind(
            \App\DataProviders\Subscription\Contracts\SubscriptionDataProvider::class,
            \App\DataProviders\Subscription\SubscriptionDataProvider::class
        );

        $this->app->bind(
            \App\DataProviders\Tag\Contracts\TagDataProvider::class,
            \App\DataProviders\Tag\TagDataProvider::class
        );

        $this->app->bind(
            \App\DataProviders\User\Contracts\UserDataProvider::class,
            \App\DataProviders\User\UserDataProvider::class
        );

        $this->app->bind(
            \App\Managers\Photo\Contracts\PhotoManager::class,
            \App\Managers\Photo\PhotoManager::class
        );

        $this->app->bind(
            \App\Managers\Subscription\Contracts\SubscriptionManager::class,
            \App\Managers\Subscription\SubscriptionManager::class
        );

        $this->app->bind(
            \App\Managers\Tag\Contracts\TagManager::class,
            \App\Managers\Tag\TagManager::class
        );

        $this->app->bind(
            \App\Managers\User\Contracts\UserManager::class,
            \App\Managers\User\UserManager::class
        );

        $this->app->bind(
            \App\Services\Photo\Contracts\ExifFetcherService::class,
            \App\Services\Photo\ExifFetcherService::class
        );

        $this->app->bind(
            \App\Services\Photo\Contracts\ThumbnailsGeneratorService::class,
            \App\Services\Photo\ThumbnailsGeneratorService::class
        );

        $this->app->bind(
            \App\Services\Trash\Contracts\TrashService::class,
            function ($app) {
                return new \App\Services\Trash\TrashService($app->make('filesystem'), config('main.storage.path.trash'));
            }
        );

        $this->app->bind(
            \App\Services\SiteMap\Contracts\SiteMapBuilderService::class,
            \App\Services\SiteMap\SiteMapBuilderService::class
        );

        $this->app->bind(
            \App\Services\Rss\Contracts\RssBuilderService::class,
            \App\Services\Rss\RssBuilderService::class
        );
    }

    /**
     * Register "package" services.
     *
     * @return void
     */
    protected function registerPackageServices(): void
    {
        $this->app->bind(
            \Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker::class,
            \Tooleks\Php\AvgColorPicker\Gd\AvgColorPicker::class
        );
    }

    /**
     * Register "lib" services.
     *
     * @return void
     */
    protected function registerLibServices(): void
    {
        $this->app->bind(
            \Lib\ExifFetcher\Contracts\ExifFetcher::class,
            \Lib\ExifFetcher\ExifFetcher::class
        );

        $this->app->bind(
            \Lib\Rss\Contracts\Builder::class,
            \Lib\Rss\Builder::class
        );

        $this->app->bind(
            \Lib\SiteMap\Contracts\Builder::class,
            \Lib\SiteMap\Builder::class
        );

        $this->app->bind(\Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator::class, function () {
            return new \Lib\ThumbnailsGenerator\ThumbnailsGenerator(config('main.photo.thumbnails'));
        });
    }
}
