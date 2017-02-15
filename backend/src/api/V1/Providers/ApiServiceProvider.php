<?php

namespace Api\V1\Providers;

use Api\V1\Http\Controllers\{
    PublishedPhotoController,
    TokenController,
    UploadedPhotoController,
    UserController
};
use Api\V1\Presenters\{
    PhotoPresenter,
    TokenPresenter,
    UploadedPhotoPresenter,
    UserPresenter
};
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
        $this->app
            ->when(TokenController::class)
            ->needs('$presenterClass')
            ->give(TokenPresenter::class);

        $this->app
            ->when(UserController::class)
            ->needs('$presenterClass')
            ->give(UserPresenter::class);

        $this->app
            ->when(UploadedPhotoController::class)
            ->needs('$presenterClass')
            ->give(UploadedPhotoPresenter::class);

        $this->app
            ->when(PublishedPhotoController::class)
            ->needs('$presenterClass')
            ->give(PhotoPresenter::class);
    }
}
