<?php

namespace App\Listeners\Photos;

use App\Services\Photos\Events\PhotoAfterSave;
use Illuminate\Events\Dispatcher;

/**
 * Class PhotoIsDraftListener
 *
 * Handles photo 'is_draft' attribute auto setup.
 *
 * @package App\Listeners\Photos
 */
class PhotoIsDraftListener
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
        $isPublished = $event->photo->description && $event->photo->path && $event->photo->relative_url;

        $event->photo->update(['is_draft' => !$isPublished]);
    }
}
