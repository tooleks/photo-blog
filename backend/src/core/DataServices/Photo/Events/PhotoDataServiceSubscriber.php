<?php

namespace Core\DataServices\Photo\Events;

use Core\DataServices\Photo\PhotoDataService;
use Core\Models\Photo;
use Core\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
            // Get all associated tags.
            $oldTags = $photo->tags()->get();
            // Detach all associated tags.
            $photo->tags()->detach();
            // Attach existing tags.
            foreach ($attributes['tags'] as $key => $value) {
                $tag = Tag::whereText($value['text'])->first();
                if (!is_null($tag)) {
                    $existingTags[] = $tag;
                    $photo->tags()->attach($tag->id);
                    unset($attributes['tags'][$key]);
                }
            }
            // Create newly added tags.
            $newTags = $photo->tags()->createMany($attributes['tags']);
            // Delete unused tags.
            $oldTags->map(function (Tag $tag) {
                $count = DB::table('tags')
                    ->leftJoin('photo_tags', 'photo_tags.tag_id', '=', 'tags.id')
                    ->where('tags.id', '=', $tag->id)
                    ->where('photo_tags.photo_id', '=', null)
                    ->count();
                if ($count)
                    $tag->delete();
            });
            // Store all tags into attribute.
            $photo->tags = (new Collection($newTags))->merge($existingTags ?? []);
        }
    }
}
