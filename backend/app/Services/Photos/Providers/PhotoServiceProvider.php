<?php

namespace App\Services\Photos\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class PhotoServiceProvider.
 * @package App\Services\Photos\Providers
 */
class PhotoServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    protected $listen = [];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->app->bind(
            \App\Services\Photos\Contracts\PhotoServiceContract::class,
            \App\Services\Photos\PhotoService::class
        );
    }
}
