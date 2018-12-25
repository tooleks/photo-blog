<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider.
 *
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerParametersBindings();

        parent::boot();
    }

    /**
     * Register model bindings.
     *
     * @return void
     */
    public function registerParametersBindings()
    {
        Route::bind('id', function ($id) {
            // Resolve 'me' value as identifier of an authorized user.
            if ($id === 'me') {
                $id = optional(Auth::user())->id;
            }
            return $id;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiV1Routes();

        $this->mapWebRoutes();

        // Public routes include the universal route that handles all incoming requests,
        // so this routes group should be the last one.
        $this->mapPublicRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiV1Routes()
    {
        Route::prefix('api/v1')
            ->middleware('api/v1')
            ->group(base_path('routes/api/v1.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "public" routes for the application.
     *
     * @return void
     */
    protected function mapPublicRoutes()
    {
        Route::middleware('public')
            ->group(base_path('routes/public.php'));
    }
}
