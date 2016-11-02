<?php

namespace App\Listeners\Photos;

use App\Services\Photos\Events\PhotoBeforeDelete;
use App\Services\Photos\Events\PhotoBeforeFind;
use App\Services\Photos\Events\PhotoAfterSave;
use App\Services\Photos\Events\PhotoBeforeValidate;
use Illuminate\Events\Dispatcher;

/**
 * Class PhotoTagsListener
 *
 * Handles photo tags validation, creating, updating, deleting.
 *
 * @package App\Listeners\Photos
 */
class PhotoTagsListener
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
            PhotoBeforeValidate::class,
            static::class . '@onPhotoBeforeValidate',
            static::PRIORITY
        );

        $events->listen(
            PhotoAfterSave::class,
            static::class . '@onPhotoAfterSave',
            static::PRIORITY
        );

        $events->listen(
            PhotoBeforeDelete::class,
            static::class . '@onPhotoBeforeDelete',
            static::PRIORITY
        );

        $events->listen(
            PhotoBeforeFind::class,
            static::class . '@onPhotoBeforeFind',
            static::PRIORITY
        );
    }

    /**
     * Handle photo before validate event.
     *
     * @param PhotoBeforeValidate $event
     */
    public function onPhotoBeforeValidate(PhotoBeforeValidate $event)
    {
        if ($event->scenario === 'create' || $event->scenario === 'update') {
            $event->validator->mergeRules('tags', [
                'required',
                'filled',
                'array',
            ]);
            $event->validator->mergeRules('tags.*.text', [
                'required',
                'filled',
                'string',
                'min:1',
                'max:255',
            ]);
        }
    }

    /**
     * Handle photo after save events.
     *
     * @param PhotoAfterSave $event
     */
    public function onPhotoAfterSave(PhotoAfterSave $event)
    {
        if ($event->scenario === 'create' || $event->scenario === 'update') {
            $event->photo->tags()->delete();
            $event->photo->tags()->detach();
            $event->photo->tags()->createMany($event->attributes['tags']);
        }
    }

    /**
     * Handle photo before delete events.
     *
     * @param PhotoBeforeDelete $event
     */
    public function onPhotoBeforeDelete(PhotoBeforeDelete $event)
    {
        $event->photo->tags()->delete();
        $event->photo->tags()->detach();
    }

    /**
     * Handle photo before find events.
     *
     * @param PhotoBeforeFind $event
     */
    public function onPhotoBeforeFind(PhotoBeforeFind $event)
    {
        $event->photo = $event->photo->with('tags');

        if ($event->scenario === 'search' && isset($event->parameters['tag'])) {
            $event->photo = $event->photo
                ->select('photos.*')
                ->join('photo_tags', 'photo_tags.photo_id', '=', 'photos.id')
                ->join('tags', 'tags.id', '=', 'photo_tags.tag_id')
                ->where('tags.text', 'like', "%{$event->parameters['tag']}%");
        }
    }
}
