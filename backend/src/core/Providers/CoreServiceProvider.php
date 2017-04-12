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
    }
}
