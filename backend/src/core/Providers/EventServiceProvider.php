<?php

namespace Core\Providers;

use Core\DataProviders\Photo\Subscribers\PhotoDataProviderSubscriber;
use Core\DataProviders\User\Events\UserDataProviderSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider.
 *
 * @package Core\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * @inheritdoc
     */
    protected $subscribe = [
        PhotoDataProviderSubscriber::class,
        UserDataProviderSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
