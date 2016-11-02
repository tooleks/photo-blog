<?php

namespace App\Listeners\Users;

use App\Services\Users\Events\UserBeforeSave;
use Illuminate\Events\Dispatcher;

/**
 * Class UserApiTokenListener
 * @package App\Listeners\Users
 */
class UserApiTokenListener
{
    /**
     * Subscribing priority.
     */
    const PRIORITY = 1000;

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserBeforeSave::class,
            static::class . '@onUserBeforeSave',
            static::PRIORITY
        );
    }

    /**
     * Handle user before save events.
     *
     * @param UserBeforeSave $event
     */
    public function onUserBeforeSave(UserBeforeSave $event)
    {
        if ($event->scenario === 'create') {
            $event->attributes['api_token'] = str_random(64);
        }
    }
}
