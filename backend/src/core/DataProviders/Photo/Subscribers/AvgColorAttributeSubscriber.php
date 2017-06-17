<?php

namespace Core\DataProviders\Photo\Subscribers;

use Core\Models\Photo;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Contracts\Filesystem\Factory as AvgColorAttribute;
use Core\DataProviders\Photo\PhotoDataProvider;
use RuntimeException;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class AvgColorAttributeSubscriber.
 *
 * @package Core\DataProviders\Photo\Subscribers
 * @property AvgColorAttribute storage
 * @property AvgColorPicker avgColorPicker
 */
class AvgColorAttributeSubscriber
{
    /**
     * AvgColorAttributeSubscriber constructor.
     *
     * @param AvgColorAttribute $storage
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(Storage $storage, AvgColorPicker $avgColorPicker)
    {
        $this->storage = $storage;
        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            PhotoDataProvider::class . '@beforeSave',
            static::class . '@onBeforeSave'
        );
    }

    /**
     * Handle before save event.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function onBeforeSave(Photo $photo, array $attributes = [], array $options = [])
    {
        if (isset($options['generate']['avg_color_attribute'])) {
            return;
        }

        if (is_null($photo->path)) {
            throw new RuntimeException('The photo path attribute is not provided.');
        }

        $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath(
            $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path
        );
    }
}
