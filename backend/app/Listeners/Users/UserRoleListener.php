<?php

namespace App\Listeners\Users;

use App\Models\Role;
use App\Services\Users\Events\UserBeforeSave;
use Illuminate\Events\Dispatcher;

/**
 * Class UserRoleListener
 * @package App\Listeners\Users
 */
class UserRoleListener
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
            $event->user->role()->associate(Role::customer()->firstOrFail());
        }
    }
}
