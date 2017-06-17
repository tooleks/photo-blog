<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\AvgColorGeneratorService as AvgColorGeneratorServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use RuntimeException;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class AvgColorGeneratorService.
 *
 * @property Storage storage
 * @property AvgColorPicker avgColorPicker
 * @package Core\Services\Photo
 */
class AvgColorGeneratorService implements AvgColorGeneratorServiceContract
{
    /**
     * AvgColorGeneratorService constructor.
     *
     * @param Storage $storage
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(Storage $storage, AvgColorPicker $avgColorPicker)
    {
        $this->storage = $storage;
        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * @inheritdoc
     */
    public function run(...$parameters)
    {
        list($photo) = $parameters;

        if (is_null($photo->path)) {
            throw new RuntimeException('The photo path is not provided.');
        }

        $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath(
            $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path
        );
    }
}
