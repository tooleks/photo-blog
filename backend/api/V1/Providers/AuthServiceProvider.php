<?php

namespace Api\V1\Providers;

use Api\V1\Policies\PhotoPolicy;
use Api\V1\Policies\UserPolicy;
use App\Models\DB\Photo;
use App\Models\DB\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider.
 *
 * @package Api\V1\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Photo::class => PhotoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
