<?php

namespace App\Services\Users\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class UserServiceProvider
 * @package App\Services\Users\Providers
 */
class UserServiceProvider extends ServiceProvider
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
            \App\Services\Users\Contracts\UserServiceContract::class,
            \App\Services\Users\UserService::class
        );
    }
}
