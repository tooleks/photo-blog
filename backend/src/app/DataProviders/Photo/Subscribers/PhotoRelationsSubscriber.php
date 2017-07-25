<?php

namespace App\DataProviders\Photo\Subscribers;

use App\DataProviders\Photo\PhotoDataProvider;
use App\Models\Photo;
use App\Models\Tag;
use App\Models\Thumbnail;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface as DbConnection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PhotoRelationsSubscriber.
 *
 * @package App\DataProviders\Photo\Subscribers
 */
class PhotoRelationsSubscriber
{
    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * PhotoRelationsSubscriber constructor.
     *
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
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
            PhotoDataProvider::class . '@beforeGetByKey',
            static::class . '@onBeforeFetch'
        );

        $events->listen(
            PhotoDataProvider::class . '@beforeGetFirst',
            static::class . '@onBeforeFetch'
        );

        $events->listen(
            PhotoDataProvider::class . '@beforeGet',
            static::class . '@onBeforeFetch'
        );

        $events->listen(
            PhotoDataProvider::class . '@beforeEach',
            static::class . '@onBeforeFetch'
        );

        $events->listen(
            PhotoDataProvider::class . '@beforePaginate',
            static::class . '@onBeforeFetch'
        );

        $events->listen(
            PhotoDataProvider::class . '@afterSave',
            static::class . '@onAfterSave'
        );

        $events->listen(
            PhotoDataProvider::class . '@beforeDelete',
            static::class . '@onBeforeDelete'
        );
    }

    /**
     * Handle before fetch event.
     *
     * @param $query
     * @param array $options
     * @return void
     */
    public function onBeforeFetch(Builder $query, array $options = []): void
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
    public function onAfterSave(Photo $photo, array $attributes = [], array $options = []): void
    {
        if (array_key_exists('with', $options)) {
            // Save with exif.
            if (in_array('exif', $options['with'], true) && isset($attributes['exif'])) {
                $this->savePhotoExif($photo, $attributes['exif']);
            }
            // Save with thumbnails.
            if (in_array('thumbnails', $options['with'], true) && isset($attributes['thumbnails'])) {
                $this->savePhotoThumbnails($photo, $attributes['thumbnails']);
            }
            // Save with tags.
            if (in_array('tags', $options['with'], true) && isset($attributes['tags'])) {
                $this->savePhotoTags($photo, $attributes['tags']);
            }
        }
    }

    /**
     * Handle before delete event.
     *
     * @param Photo $photo
     * @param array $options
     * @return void
     */
    public function onBeforeDelete(Photo $photo, array $options = []): void
    {
        // Delete exif.
        $photo->exif()->delete();
        // Delete thumbnails.
        $photo->thumbnails()->detach();
        Thumbnail::deleteAllDetached();
        // Delete tags.
        $photo->tags()->detach();
        Tag::deleteAllDetached();
    }

    /**
     * Save photo exif.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    private function savePhotoExif(Photo $photo, array $attributes): void
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
     * @return void
     */
    private function savePhotoThumbnails(Photo $photo, array $records): void
    {
        $photo->thumbnails()->detach();

        $thumbnails = $photo->thumbnails()->createMany($records);

        $photo->thumbnails = new Collection($thumbnails);

        Thumbnail::deleteAllDetached();
    }

    /**
     * Save photo tags.
     *
     * @param Photo $photo
     * @param array $records
     * @return void
     */
    private function savePhotoTags(Photo $photo, array $records): void
    {
        $photo->tags()->detach();

        // Attach existing tags.
        foreach ($records as $key => $attributes) {
            $tag = Tag::whereValue($attributes['value'])->first();
            if (!is_null($tag)) {
                $existingTags[] = $tag;
                $photo->tags()->attach($tag->id);
                unset($records[$key]);
            }
        }

        $newTags = $photo->tags()->createMany($records);

        $photo->tags = (new Collection($newTags))->merge($existingTags ?? []);

        Tag::deleteAllDetached();
    }
}
