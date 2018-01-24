<?php

namespace App\Services\Photo;

use App\Services\Photo\Contracts\ThumbnailsGeneratorService as ThumbnailsGeneratorServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class ThumbnailsGeneratorService.
 *
 * @package App\Services\Photo
 */
class ThumbnailsGeneratorService implements ThumbnailsGeneratorServiceContract
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var ThumbnailsGenerator
     */
    private $thumbnailsGenerator;

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
    public function generate(string $filePath): array
    {
        $thumbnails = $this->thumbnailsGenerator->generateThumbnails(
            $this->storage->getDriver()->getAdapter()->getPathPrefix() . $filePath
        );

        foreach ($thumbnails as $thumbnail) {
            $thumbnailPath = dirname($filePath) . '/' . basename($thumbnail['path']);
            $data[] = [
                'path' => $thumbnailPath,
                'width' => $thumbnail['width'],
                'height' => $thumbnail['height'],
            ];
        }

        return $data ?? [];
    }
}
