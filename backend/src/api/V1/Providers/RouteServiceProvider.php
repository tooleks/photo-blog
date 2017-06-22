<?php

namespace Api\V1\Providers;

use Core\DataProviders\Photo\Criterias\IsPublished;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider.
 *
 * @package Api\V1\Providers
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
    protected $namespace = 'Api\V1\Http\Controllers';

    /**
     * @inheritdoc
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
        Route::group([
            'middleware' => 'api.v1',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/api.v1.php');
        });
    }

    /**
     * Register model bindings.
     *
     * @return void
     */
    public function registerModelBindings()
    {
        Route::bind('published_photo', function ($id) {
            return $this->app
                ->make(\Core\Managers\Photo\Contracts\PhotoManager::class)
                ->getPublishedById($id);
        });

        Route::bind('photo', function ($id) {
            return $this->app
                ->make(\Core\Managers\Photo\Contracts\PhotoManager::class)
                ->getById($id);
        });

        Route::bind('subscription', function ($token) {
            return $this->app
                ->make(\Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider::class)
                ->getByToken($token);
        });

        Route::bind('user', function ($id) {
            return $this->app
                ->make(\Core\DataProviders\User\Contracts\UserDataProvider::class)
                ->getById($id);
        });
    }
}
