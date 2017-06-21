<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\AvgColorGeneratorService as AvgColorGeneratorServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class AvgColorGeneratorService.
 *
 * @property Storage storage
 * @property AvgColorPicker avgColorPicker
 * @property string path
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
     * Fetch parameters.
     *
     * @param array $parameters
     */
    protected function fetchParameters(array $parameters)
    {
        list($this->path) = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function run(...$parameters): string
    {
        $this->fetchParameters($parameters);

        return $this->avgColorPicker->getImageAvgHexByPath(
            $this->storage->getDriver()->getAdapter()->getPathPrefix() . $this->path
        );
    }
}
