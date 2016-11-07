<?php

namespace App\Listeners\Photos;

use App\Models\PhotoThumbnail;
use App\Services\Photos\Events\PhotoBeforeDelete;
use App\Services\Photos\Events\PhotoAfterSave;
use App\Services\Photos\Events\PhotoBeforeFind;
use App\Services\Thumbnails\Contracts\ThumbnailServiceContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class PhotoThumbnailsListener
 *
 * Handles photo thumbnails creating, updating, deleting.
 *
 * @property Filesystem fileSystem
 * @property ThumbnailServiceContract thumbnailService
 * @package App\Listeners\Photos
 */
class PhotoThumbnailsListener
{
    /**
     * Subscribing priority.
     */
    const PRIORITY = 1000;

    /**
     * PhotoThumbnailsListener constructor.
     *
     * @param Filesystem $fileSystem
     * @param ThumbnailServiceContract $thumbnailService
     */
    public function __construct(Filesystem $fileSystem, ThumbnailServiceContract $thumbnailService)
    {
        $this->fileSystem = $fileSystem;
        $this->thumbnailService = $thumbnailService;
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
     * Handle photo after save events.
     *
     * @param PhotoAfterSave $event
     */
    public function onPhotoAfterSave(PhotoAfterSave $event)
    {
        if ($event->scenario === 'upload') {
            $event->photo->thumbnails()->get()->map(function (PhotoThumbnail $thumbnail) {
                $this->fileSystem->delete($thumbnail->path);
            });
            $event->photo->thumbnails()->delete();
            if ($this->fileSystem->exists($event->photo->path)) {
                foreach (config('main.photo.thumbnail.sizes') as $size) {
                    $thumbnailFilePath = $this->thumbnailService->createThumbnail(
                        $event->photo->path,
                        $size['width'],
                        $size['height']
                    );
                    $event->photo->thumbnails()->create([
                        'width' => $size['width'],
                        'height' => $size['height'],
                        'path' => $thumbnailFilePath,
                        'relative_url' => $this->fileSystem->url($thumbnailFilePath),
                    ]);
                }
            }
        }
    }

    /**
     * Handle photo before delete events.
     *
     * @param PhotoBeforeDelete $event
     */
    public function onPhotoBeforeDelete(PhotoBeforeDelete $event)
    {
        $event->photo->thumbnails()->get()->map(function (PhotoThumbnail $thumbnail) {
            $this->fileSystem->delete($thumbnail->path);
        });
        $event->photo->thumbnails()->delete();
    }

    /**
     * Handle photo before find events.
     *
     * @param PhotoBeforeFind $event
     */
    public function onPhotoBeforeFind(PhotoBeforeFind $event)
    {
        $event->photo = $event->photo->with('thumbnails');
    }
}
