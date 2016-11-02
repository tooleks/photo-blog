<?php

namespace App\Listeners\Photos;

use App\Services\Photos\Events\PhotoAfterDelete;
use App\Services\Photos\Events\PhotoBeforeSave;
use App\Services\Photos\Events\PhotoBeforeValidate;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;

/**
 * Class PhotoFileListener
 *
 * Handles photo file validation, uploading, deletion.
 *
 * @property Request request
 * @property Filesystem fileSystem
 * @package App\Listeners\Photos
 */
class PhotoFileListener
{
    /**
     * Subscribing priority.
     */
    const PRIORITY = 1000;

    /**
     * PhotoFileListener constructor.
     *
     * @param Request $request
     * @param Filesystem $fileSystem
     */
    public function __construct(Request $request, Filesystem $fileSystem)
    {
        $this->request = $request;
        $this->fileSystem = $fileSystem;
    }

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
            PhotoBeforeSave::class,
            static::class . '@onPhotoBeforeSave',
            static::PRIORITY
        );

        $events->listen(
            PhotoAfterDelete::class,
            static::class . '@onPhotoAfterDelete',
            static::PRIORITY
        );
    }

    /**
     * Handle photo before validate events.
     *
     * @param PhotoBeforeValidate $event
     */
    public function onPhotoBeforeValidate(PhotoBeforeValidate $event)
    {
        $fileAttributeRules = [
            'required',
            'file',
            'mimes:jpeg,png',
            'min:' . config('main.upload.min-size'),
            'max:' . config('main.upload.max-size'),
        ];

        $rules = $event->validator->getRules();

        if ($event->scenario === 'upload') {
            $attributes['file'] = $event->attributes['file'];
            $event->attributes = $attributes;
            $event->validator->setRules(['file' => $fileAttributeRules]);
        }

        if ($event->scenario === 'create' || $event->scenario === 'update') {
            // We'll set these attribute values manually in the @onPhotoBeforeSave method.
            unset($rules['path'], $rules['relative_url']);
            if (!$event->photo->is_uploaded) {
                $rules += ['file' => $fileAttributeRules];
            }
            $event->validator->setRules($rules);
        }
    }

    /**
     * Handle photo before save events.
     *
     * @param PhotoBeforeSave $event
     */
    public function onPhotoBeforeSave(PhotoBeforeSave $event)
    {
        if ($event->scenario === 'upload') {
            $path = $this->request->file('file')->store(sprintf('%s/%s', config('main.path.photos'), $event->photo->id));
            if ($event->photo->path !== $path) {
                $this->fileSystem->delete($event->photo->path);
            }
            $event->attributes['path'] = $path;
            $event->attributes['relative_url'] = $this->fileSystem->url($path);
        }
    }

    /**
     * Handle photo after delete events.
     *
     * @param PhotoAfterDelete $event
     */
    public function onPhotoAfterDelete(PhotoAfterDelete $event)
    {
        if ($event->count) {
            $this->fileSystem->delete($event->photo->path);
            $this->fileSystem->deleteDirectory(pathinfo($event->photo->path, PATHINFO_DIRNAME));
        }
    }
}
