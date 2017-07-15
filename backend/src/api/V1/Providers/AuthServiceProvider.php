<?php

namespace Api\V1\Providers;

use Api\V1\Policies\ResourcePolicy;
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

        $this->registerTokens();
    }

    /**
     * Register the application's gates.
     *
     * @return void
     */
    public function registerGates()
    {
        $resourcePolicy = new ResourcePolicy;

        Gate::define('create-resource', [$resourcePolicy, 'create']);
        Gate::define('get-resource', [$resourcePolicy, 'get']);
        Gate::define('update-resource', [$resourcePolicy, 'update']);
        Gate::define('delete-resource', [$resourcePolicy, 'delete']);
    }

    /**
     * Register the application's tokens.
     *
     * @return void
     */
    public function registerTokens()
    {
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addHour());
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
