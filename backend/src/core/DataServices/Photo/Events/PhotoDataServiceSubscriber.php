<?php

namespace Core\DataServices\Photo\Events;

use Core\Models\Photo;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;

/**
 * Class PhotoDataServiceSubscriber.
 *
 * @package Core\DataServices\Photo\Events
 */
class PhotoDataServiceSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('events.photoDataService.beforeGetById', static::class . '@onBeforeGet');
        $events->listen('events.photoDataService.beforeGetFirst', static::class . '@onBeforeGet');
        $events->listen('events.photoDataService.beforeGet', static::class . '@onBeforeGet');
        $events->listen('events.photoDataService.afterSave', static::class . '@onAfterSave');
    }

    /**
     * Handle before get event.
     *
     * @param $query
     * @param array $options
     * @return void
     */
    public function onBeforeGet($query, array $options)
    {
        $query->with('exif');

        $query->with('thumbnails');

        $query->with('tags');
    }

    /**
     * Handle after save event.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function onAfterSave($photo, array $attributes = [], array $options = [])
    {
        if (array_key_exists('exif', $attributes)) {
            $photo->exif()->delete();
            $exif = $photo->exif()->create($attributes['exif']);
            $photo->exif = $exif;
        }

        if (array_key_exists('thumbnails', $attributes)) {
            $photo->thumbnails()->delete();
            $photo->thumbnails()->detach();
            $tags = $photo->thumbnails()->createMany($attributes['thumbnails']);
            $photo->thumbnails = new Collection($tags);
        }

        if (array_key_exists('tags', $attributes)) {
            $photo->tags()->delete();
            $photo->tags()->detach();
            $tags = $photo->tags()->createMany($attributes['tags']);
            $photo->tags = new Collection($tags);
        }
    }
}
