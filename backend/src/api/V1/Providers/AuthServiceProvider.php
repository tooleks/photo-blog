<?php

namespace Api\V1\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider.
 *
 * @package Api\V1\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerGates();

        $this->registerOAuth();
    }

    /**
     * Register the application's gates.
     *
     * @return void
     */
    public function registerGates()
    {
        $resourcePolicy = $this->app->make(\Api\V1\Policies\ResourcePolicy::class);

        Gate::define('create-resource', [$resourcePolicy, 'create']);
        Gate::define('get-resource', [$resourcePolicy, 'get']);
        Gate::define('update-resource', [$resourcePolicy, 'update']);
        Gate::define('delete-resource', [$resourcePolicy, 'delete']);
    }

    /**
     * Register the application's OAuth server.
     *
     * @return void
     */
    public function registerOAuth()
    {
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addHour());
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
