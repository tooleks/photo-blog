<?php

namespace App\Services\Thumbnails\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class ThumbnailServiceProvider.
 * @package App\Services\Photos\Providers
 */
class ThumbnailServiceProvider extends ServiceProvider
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
            \App\Services\Thumbnails\Contracts\ThumbnailServiceContract::class,
            \App\Services\Thumbnails\ThumbnailService::class
        );
    }
}
