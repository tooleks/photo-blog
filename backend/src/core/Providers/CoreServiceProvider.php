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
            \Core\DataServices\Photo\Contracts\PhotoDataService::class,
            \Core\DataServices\Photo\PhotoDataService::class
        );

        $this->app->bind(
            \Core\DataServices\Subscription\Contracts\SubscriptionDataService::class,
            \Core\DataServices\Subscription\SubscriptionDataService::class
        );

        $this->app->bind(
            \Core\DataServices\Tag\Contracts\TagDataService::class,
            \Core\DataServices\Tag\TagDataService::class
        );

        $this->app->bind(
            \Core\DataServices\User\Contracts\UserDataService::class,
            \Core\DataServices\User\UserDataService::class
        );
    }
}
