<?php

namespace Api\V1\Providers;

use App\Models\DB\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
    }

    /**
     * Register the application's gates.
     *
     * @return void
     */
    public function registerGates()
    {
        Gate::define('access-resource', function (User $authUser, $resourceClass, $resourceId) {
            // If authenticated user is administrator allow access to resource.
            if ($authUser->isAdministrator()) {
                return true;
            }

            $resource = $resourceClass::select('user_id')->whereId($resourceId)->first();

            if (is_null($resource)) {
                throw new ModelNotFoundException('Resource not found.');
            }

            // If authenticated user is the resource owner allow access to resource.
            if ($authUser->id == $resource->user_id) {
                return true;
            }

            // Otherwise deny access to resource.
            return false;
        });
    }
}
