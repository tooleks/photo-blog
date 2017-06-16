<?php

namespace Core\DataProviders\Photo\Subscribers;

use Core\Models\Photo;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Core\DataProviders\Photo\PhotoDataProvider;

/**
 * Class StorageSubscriber.
 *
 * @property Storage storage
 * @package Core\DataProviders\Photo\Subscribers
 */
class StorageSubscriber
{
    /**
     * StorageSubscriber constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            PhotoDataProvider::class . '@afterDelete',
            static::class . '@onAfterDelete'
        );
    }

    /**
     * Handle after delete event.
     *
     * @param Photo $photo
     * @param int $deleted
     * @param array $options
     * @return void
     */
    public function onAfterDelete(Photo $photo, int $deleted, array $options)
    {
        if ($deleted) {
            $this->storage->deleteDirectory($photo->directory_path);
        }
    }
}
