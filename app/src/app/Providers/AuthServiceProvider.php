<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Core\Entities\UserEntity;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider.
 *
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerResourcePolicy();

        $this->registerGates();

        $this->registerOAuth();
    }

    /**
     * Register the application's resource policy.
     *
     * @return void
     */
    public function registerResourcePolicy(): void
    {
        $resourcePolicy = $this->app->make(\Api\V1\Policies\ResourcePolicy::class);
        Gate::define('create-resource', [$resourcePolicy, 'create']);
        Gate::define('get-resource', [$resourcePolicy, 'get']);
        Gate::define('update-resource', [$resourcePolicy, 'update']);
        Gate::define('delete-resource', [$resourcePolicy, 'delete']);
    }

    /**
     * Register the application's gates.
     *
     * @return void
     */
    public function registerGates(): void
    {
        Gate::define('access-administrator-part', function (User $authUser) {
            return $authUser->toEntity()->isAdministrator();
        });

        Gate::define('view-user-contacts', function (User $authUser, UserEntity $user) {
            return $authUser->toEntity()->isAdministrator() || $authUser->id === $user->getId();
        });

        Gate::define('view-unpublished-posts', function (User $authUser) {
            return $authUser->toEntity()->isAdministrator();
        });
    }

    /**
     * Register the application's OAuth server.
     *
     * @return void
     */
    public function registerOAuth(): void
    {
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addHours(12));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
