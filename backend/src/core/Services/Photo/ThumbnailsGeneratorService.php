<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\ThumbnailsGeneratorService as ThumbnailsGeneratorServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class ThumbnailsGeneratorService.
 *
 * @package Core\Services\Photo
 */
class ThumbnailsGeneratorService implements ThumbnailsGeneratorServiceContract
{
    private $storage;
    private $thumbnailsGenerator;
    private $path;

    /**
     * ThumbnailsGeneratorService constructor.
     *
     * @param Storage $storage
     * @param ThumbnailsGenerator $thumbnailsGenerator
     */
    public function __construct(Storage $storage, ThumbnailsGenerator $thumbnailsGenerator)
    {
        $this->storage = $storage;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
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
    public function run(...$parameters): array
    {
        $this->fetchParameters($parameters);

        $thumbnails = $this->thumbnailsGenerator->generateThumbnails(
            $this->storage->getDriver()->getAdapter()->getPathPrefix() . $this->path
        );

        foreach ($thumbnails as $thumbnail) {
            $thumbnailPath = dirname($this->path) . '/' . basename($thumbnail['path']);
            $data[] = [
                'path' => $thumbnailPath,
                'width' => $thumbnail['width'],
                'height' => $thumbnail['height'],
            ];
        }

        return $data ?? [];
    }
}
