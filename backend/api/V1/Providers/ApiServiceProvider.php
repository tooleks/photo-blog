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
use Illuminate\Support\ServiceProvider;

/**
 * Class ApiServiceProvider
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
        $this->app
            ->when(TokenController::class)
            ->needs(Resource::class)
            ->give(TokenResource::class);

        $this->app
            ->when(UserController::class)
            ->needs(Resource::class)
            ->give(UserResource::class);

        $this->app
            ->when(UploadedPhotoController::class)
            ->needs(Resource::class)
            ->give(UploadedPhotoResource::class);

        $this->app
            ->when(PhotoController::class)
            ->needs(Resource::class)
            ->give(PhotoResource::class);
    }
}
