<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\ThumbnailsGeneratorService as ThumbnailsGeneratorServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;
use RuntimeException;

/**
 * Class ThumbnailsGeneratorService.
 *
 * @property Storage storage
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @package Core\Services\Photo
 */
class ThumbnailsGeneratorService implements ThumbnailsGeneratorServiceContract
{
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
     * @inheritdoc
     */
    public function run(...$parameters): array
    {
        list($photo) = $parameters;

        if (is_null($photo->path)) {
            throw new RuntimeException('The photo path is not provided.');
        }

        $thumbnails = $this->thumbnailsGenerator->generateThumbnails(
            $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path
        );

        foreach ($thumbnails as $thumbnail) {
            $thumbnailRelPath = pathinfo($photo->path, PATHINFO_DIRNAME) . '/' . pathinfo($thumbnail['path'], PATHINFO_BASENAME);
            $data[] = [
                'path' => $thumbnailRelPath,
                'relative_url' => $this->storage->url($thumbnailRelPath),
                'width' => $thumbnail['width'],
                'height' => $thumbnail['height'],
            ];
        }

        return $data ?? [];
    }
}
