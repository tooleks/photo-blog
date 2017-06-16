<?php

namespace Core\Providers;

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
        \Core\DataProviders\Photo\Subscribers\StorageSubscriber::class,
        \Core\DataProviders\Photo\Subscribers\PhotoDataProviderSubscriber::class,
        \Core\DataProviders\User\Events\UserDataProviderSubscriber::class,
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
