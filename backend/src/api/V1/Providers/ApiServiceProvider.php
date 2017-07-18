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
        $this->app->bind(
            \Api\V1\Http\Proxy\Contracts\OAuthProxy::class,
            \Api\V1\Http\Proxy\CookieOAuthProxy::class
        );
    }
}
