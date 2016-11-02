<?php

namespace App\Listeners\Photos;

use App\Services\Photos\Events\PhotoAfterSave;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Events\Dispatcher;

/**
 * Class PhotoUserIdListener
 *
 * Handles photo 'user_id' attribute auto setup.
 *
 * @property Guard guard
 * @package App\Listeners\Photos
 */
class PhotoUserIdListener
{
    /**
     * Subscribing priority.
     */
    const PRIORITY = 1000;

    /**
     * PhotoUserIdListener constructor.
     *
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            PhotoAfterSave::class,
            static::class . '@onPhotoAfterSave',
            static::PRIORITY
        );
    }

    /**
     * Handle photo after save events.
     *
     * @param PhotoAfterSave $event
     */
    public function onPhotoAfterSave(PhotoAfterSave $event)
    {
        if ($event->scenario === 'create') {
            $event->photo->update(['user_id' => $this->guard->user()->id]);
        }
    }
}
