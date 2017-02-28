<?php

namespace Lib\ThumbnailsGenerator;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator as ThumbnailsGeneratorContract;

/**
 * Class ThumbnailsGenerator.
 *
 * @property Filesystem fileSystem
 * @property array config
 * @package Lib\ThumbnailsGenerator
 */
class ThumbnailsGenerator implements ThumbnailsGeneratorContract
{
    const THUMBNAIL_MODE_INSET = 'inset';
    const THUMBNAIL_MODE_OUTBOUND = 'outbound';

    /**
     * ThumbnailsGenerator constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function generateThumbnails(string $originalImageFilePath) : array
    {
        $metaData = [];

        $originalImage = (new Imagine)->open($originalImageFilePath);

        $this->eachConfiguredThumbnail(function ($config) use ($originalImage, &$metaData) {
            // Generate thumbnail image.
            $thumbnailImage = $originalImage->thumbnail(new Box($config['size']['width'], $config['size']['height']),
                $config['mode']);
            // Generate thumbnail file path.
            $thumbnailFilePath = $this->getThumbnailFilePath($originalImage->metadata()->get('filepath'),
                $thumbnailImage->getSize()->getWidth(), $thumbnailImage->getSize()->getHeight());
            // Save thumbnail file.
            $thumbnailImage->save($thumbnailFilePath, ['quality' => $config['quality']]);
            // Append thumbnail metadata.
            $metaData[] = [
                'path' => $thumbnailFilePath,
                'width' => $thumbnailImage->getSize()->getWidth(),
                'height' => $thumbnailImage->getSize()->getHeight(),
            ];
        });

        return $metaData ?? [];
    }

    /**
     * Each configured thumbnail.
     *
     * @param Closure $closure
     */
    private function eachConfiguredThumbnail(Closure $closure)
    {
        foreach ($this->config as $config) {
            $closure($config);
        }
    }

    /**
     * Get thumbnail file path.
     *
     * @param string $originalImageFilePath
     * @param string $width
     * @param string $height
     * @return string
     */
    private function getThumbnailFilePath(string $originalImageFilePath, string $width, string $height) : string
    {
        return sprintf(
            '%s/%s_%sx%s.%s',
            pathinfo($originalImageFilePath, PATHINFO_DIRNAME),     // Subdirectory.
            pathinfo($originalImageFilePath, PATHINFO_FILENAME),    // File name.
            $width,                                                 // Width suffix.
            $height,                                                // Height suffix.
            pathinfo($originalImageFilePath, PATHINFO_EXTENSION));  // Extension.
    }
}
