<?php

namespace Core\DataServices\Photo\Subscribers;

use Core\DataServices\Photo\PhotoDataService;
use Core\Models\Photo;
use Core\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PhotoDataServiceSubscriber.
 *
 * @property ConnectionInterface dbConnection
 * @package Core\DataServices\Photo\Subscribers
 */
class PhotoDataServiceSubscriber
{
    /**
     * PhotoDataServiceSubscriber constructor.
     *
     * @param ConnectionInterface $dbConnection
     */
    public function __construct(ConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

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
        if (!array_key_exists('save', $options)) {
            return;
        }

        if (in_array('exif', $options['save']) && array_key_exists('exif', $attributes)) {
            $this->savePhotoExif($photo, $attributes['exif']);
        }

        if (in_array('thumbnails', $options['save']) && array_key_exists('thumbnails', $attributes)) {
            $this->savePhotoThumbnails($photo, $attributes['thumbnails']);
        }

        if (in_array('tags', $options['save']) && array_key_exists('tags', $attributes)) {
            $this->savePhotoTags($photo, $attributes['tags']);
        }
    }

    /**
     * Save photo exif.
     *
     * @param Photo $photo
     * @param array $attributes
     */
    private function savePhotoExif(Photo $photo, array $attributes)
    {
        $photo->exif()->delete();

        $exif = $photo->exif()->create($attributes);

        $photo->exif = $exif;
    }

    /**
     * Save photo thumbnails.
     *
     * @param Photo $photo
     * @param array $records
     */
    private function savePhotoThumbnails(Photo $photo, array $records)
    {
        $photo->thumbnails()->detach();

        $thumbnails = $photo->thumbnails()->createMany($records);

        $photo->thumbnails = new Collection($thumbnails);

        // Delete unused thumbnails.
        $this->dbConnection
            ->table('thumbnails')
            ->leftJoin('photo_thumbnails', 'photo_thumbnails.thumbnail_id', '=', 'thumbnails.id')
            ->whereNull('photo_thumbnails.photo_id')
            ->delete();
    }

    /**
     * Save photo tags.
     *
     * @param Photo $photo
     * @param array $records
     */
    private function savePhotoTags(Photo $photo, array $records)
    {
        $photo->tags()->detach();

        // Attach existing tags.
        foreach ($records as $key => $attributes) {
            $tag = Tag::whereText($attributes['text'])->first();
            if (!is_null($tag)) {
                $existingTags[] = $tag;
                $photo->tags()->attach($tag->id);
                unset($records[$key]);
            }
        }

        $newTags = $photo->tags()->createMany($records);

        $photo->tags = (new Collection($newTags))->merge($existingTags ?? []);

        // Delete unused tags.
        $this->dbConnection
            ->table('tags')
            ->leftJoin('photo_tags', 'photo_tags.tag_id', '=', 'tags.id')
            ->whereNull('photo_tags.photo_id')
            ->delete();
    }
}
