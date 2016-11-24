<?php

namespace Api\V1\Providers;

use Api\V1\Models\Presenters\PhotoPresenter;
use Api\V1\Models\Presenters\UploadedPhotoPresenter;
use Api\V1\Models\Presenters\UserPresenter;
use Api\V1\Policies\PhotoPolicy;
use Api\V1\Policies\UploadedPhotoPolicy;
use Api\V1\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        UserPresenter::class => UserPolicy::class,
        UploadedPhotoPresenter::class => UploadedPhotoPolicy::class,
        PhotoPresenter::class => PhotoPolicy::class,
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
