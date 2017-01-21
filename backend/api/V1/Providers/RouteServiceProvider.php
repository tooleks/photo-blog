<?php

namespace Api\V1\Providers;

use Api\V1\Http\Resources\PhotoResource;
use Api\V1\Http\Resources\UploadedPhotoResource;
use Api\V1\Http\Resources\UserResource;
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
            return $this->app->make(UserResource::class)->getById($id);
        });

        Route::bind('uploadedPhoto', function (int $id) {
            return $this->app->make(UploadedPhotoResource::class)->getById($id);
        });

        Route::bind('photo', function (int $id) {
            return $this->app->make(PhotoResource::class)->getById($id);
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
}
