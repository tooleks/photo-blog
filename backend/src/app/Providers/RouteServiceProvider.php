<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider.
 *
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerModelBindings();
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
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
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
            ->namespace('Api\V1\Http\Controllers')
            ->group(base_path('routes/api/v1.php'));
    }

    /**
     * Register model bindings.
     *
     * @return void
     */
    public function registerModelBindings()
    {
        Route::bind('photo', function ($id) {
            /** @var \App\Managers\Photo\Contracts\PhotoManager $manager */
            $manager = $this->app->make(\App\Managers\Photo\Contracts\PhotoManager::class);
            $model = $manager->getById((int) $id);
            return $model;
        });

        Route::bind('post', function ($id) {
            /** @var \App\Managers\Post\Contracts\PostManager $manager */
            $manager = $this->app->make(\App\Managers\Post\Contracts\PostManager::class);
            $model = $manager->getById((int) $id);
            return $model;
        });

        Route::bind('subscription', function ($token) {
            /** @var \App\Managers\Subscription\Contracts\SubscriptionManager $manager */
            $manager = $this->app->make(\App\Managers\Subscription\Contracts\SubscriptionManager::class);
            $model = $manager->getByToken((string) $token);
            return $model;
        });

        Route::bind('user', function ($id) {
            $id = ($id === 'me') ? optional(Auth::user())->id : $id;
            /** @var \App\Managers\User\Contracts\UserManager $manager */
            $manager = $this->app->make(\App\Managers\User\Contracts\UserManager::class);
            $model = $manager->getById((int) $id);
            return $model;
        });
    }
}
