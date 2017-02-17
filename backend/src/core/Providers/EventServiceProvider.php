<?php

namespace Core\Providers;

use Core\DataServices\Photo\Events\PhotoDataServiceSubscriber;
use Core\DataServices\User\Events\UserDataServiceSubscriber;
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
        PhotoDataServiceSubscriber::class,
        UserDataServiceSubscriber::class,
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
