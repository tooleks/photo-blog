<?php

namespace Core\DataServices\Photo\Events;

use Core\DataServices\Photo\PhotoDataService;
use Core\Models\Photo;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
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
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            PhotoDataService::class . '@beforeGetById',
            static::class . '@onBeforeGet'
        );

        $events->listen(
            PhotoDataService::class . '@beforeGetFirst',
            static::class . '@onBeforeGet'
        );

        $events->listen(
            PhotoDataService::class . '@beforeGet',
            static::class . '@onBeforeGet'
        );

        $events->listen(
            PhotoDataService::class . '@afterSave',
            static::class . '@onAfterSave'
        );
    }

    /**
     * Handle before get event.
     *
     * @param $query
     * @param array $options
     * @return void
     */
    public function onBeforeGet(Builder $query, array $options)
    {
        $query->with('exif', 'thumbnails', 'tags');
    }

    /**
     * Handle after save event.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function onAfterSave(Photo $photo, array $attributes = [], array $options = [])
    {
        if (in_array('exif', $options) && array_key_exists('exif', $attributes)) {
            $photo->exif()->delete();
            $exif = $photo->exif()->create($attributes['exif']);
            $photo->exif = $exif;
        }

        if (in_array('thumbnails', $options) && array_key_exists('thumbnails', $attributes)) {
            $thumbnails = $photo->thumbnails()->get();
            $photo->thumbnails()->detach();
            foreach ($thumbnails as $thumbnail)
                $thumbnail->delete();
            $thumbnails = $photo->thumbnails()->createMany($attributes['thumbnails']);
            $photo->thumbnails = new Collection($thumbnails);
        }

        if (in_array('tags', $options) && array_key_exists('tags', $attributes)) {
            $tags = $photo->tags()->get();
            $photo->tags()->detach();
            foreach ($tags as $tag)
                $tag->delete();
            $tags = $photo->tags()->createMany($attributes['tags']);
            $photo->tags = new Collection($tags);
        }
    }
}
