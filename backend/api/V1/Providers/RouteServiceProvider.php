<?php

namespace Api\V1\Providers;

use Api\V1\Services\PhotoService;
use Api\V1\Services\UploadedPhotoService;
use Api\V1\Services\UserService;
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
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('user', function (int $id) {
            return $this->getModelBindingMap()[Route::currentRouteName()]($id);
        });

        Route::bind('photo', function (int $id) {
            return $this->getModelBindingMap()[Route::currentRouteName()]($id);
        });
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
     * Get model binding map.
     *
     * @return array
     */
    protected function getModelBindingMap()
    {
        return [
            'update_uploaded_photo' => function ($id) {
                return $this->app->make(UploadedPhotoService::class)->getById($id);
            },
            'get_uploaded_photo' => function ($id) {
                return $this->app->make(UploadedPhotoService::class)->getById($id);
            },
            'delete_uploaded_photo' => function ($id) {
                return $this->app->make(UploadedPhotoService::class)->getById($id);
            },
            'update_photo' => function ($id) {
                return $this->app->make(PhotoService::class)->getById($id);
            },
            'get_photo' => function ($id) {
                return $this->app->make(PhotoService::class)->getById($id);
            },
            'delete_photo' => function ($id) {
                return $this->app->make(PhotoService::class)->getById($id);
            },
            'update_user' => function ($id) {
                return $this->app->make(UserService::class)->getById($id);
            },
            'get_user' => function ($id) {
                return $this->app->make(UserService::class)->getById($id);
            },
            'delete_user' => function ($id) {
                return $this->app->make(UserService::class)->getById($id);
            },
        ];
    }
}
