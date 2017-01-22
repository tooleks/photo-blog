<?php

namespace Api\V1\Providers;

use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Http\Controllers\PhotoController;
use Api\V1\Http\Controllers\TokenController;
use Api\V1\Http\Controllers\UploadedPhotoController;
use Api\V1\Http\Controllers\UserController;
use Api\V1\Http\Resources\PhotoResource;
use Api\V1\Http\Resources\TokenResource;
use Api\V1\Http\Resources\UploadedPhotoResource;
use Api\V1\Http\Resources\UserResource;
use Api\V1\Models\Presenters\PhotoCollectionPresenter;
use Api\V1\Models\Presenters\PhotoPresenter;
use Api\V1\Models\Presenters\TokenPresenter;
use Api\V1\Models\Presenters\UploadedPhotoPresenter;
use Api\V1\Models\Presenters\UserPresenter;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApiServiceProvider.
 *
 * @package Api\V1\Providers
 */
class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTokenServices();
        $this->registerUserServices();
        $this->registerUploadedPhotoServices();
        $this->registerPhotoResourceServices();
    }

    /**
     * Register token application services.
     *
     * @return void
     */
    protected function registerTokenServices()
    {
        $this->app
            ->when(TokenController::class)
            ->needs(Resource::class)
            ->give(TokenResource::class);

        $this->app
            ->when(TokenController::class)
            ->needs('$presenterClass')
            ->give(TokenPresenter::class);
    }

    /**
     * Register user application services.
     *
     * @return void
     */
    protected function registerUserServices()
    {
        $this->app
            ->when(UserController::class)
            ->needs(Resource::class)
            ->give(UserResource::class);

        $this->app
            ->when(UserController::class)
            ->needs('$presenterClass')
            ->give(UserPresenter::class);
    }

    /**
     * Register uploaded photo application services.
     *
     * @return void
     */
    protected function registerUploadedPhotoServices()
    {
        $this->app
            ->when(UploadedPhotoController::class)
            ->needs(Resource::class)
            ->give(UploadedPhotoResource::class);

        $this->app
            ->when(UploadedPhotoController::class)
            ->needs('$presenterClass')
            ->give(UploadedPhotoPresenter::class);
    }

    /**
     * Register photo application services.
     *
     * @return void
     */
    protected function registerPhotoResourceServices()
    {
        $this->app
            ->when(PhotoController::class)
            ->needs(Resource::class)
            ->give(PhotoResource::class);

        $this->app
            ->when(PhotoController::class)
            ->needs('$presenterClass')
            ->give(PhotoPresenter::class);
    }
}
