<?php

namespace Lib\ThumbnailsGenerator;

use Illuminate\Contracts\Filesystem\Filesystem;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator as ThumbnailsGeneratorContract;
use Lib\ThumbnailsGenerator\Exceptions\ThumbnailException;

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
     * @param Filesystem $fileSystem
     * @param array $config
     */
    public function __construct(Filesystem $fileSystem, array $config)
    {
        $this->fileSystem = $fileSystem;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function generateThumbnails(string $originalFilePath) : array
    {
        $originalFileContent = $this->fileSystem->get($originalFilePath);

        foreach ($this->config as $config) {
            $thumbnailImage = $this->getThumbnailImage(
                $originalFileContent,
                $config['size']['width'],
                $config['size']['height'],
                $config['mode']
            );

            $thumbnailFilePath = $this->getThumbnailFilePath(
                $originalFilePath,
                $thumbnailImage->getSize()->getWidth(),
                $thumbnailImage->getSize()->getHeight()
            );

            if (!$this->fileSystem->put($thumbnailFilePath, $thumbnailImage->get($this->getThumbnailExtension($originalFilePath)))) {
                throw new ThumbnailException(sprintf('An error occurred while saving thumbnail file "%s".', $thumbnailFilePath));
            }

            $metaData[] = [
                'width' => $thumbnailImage->getSize()->getWidth(),
                'height' => $thumbnailImage->getSize()->getHeight(),
                'path' => $thumbnailFilePath,
                'relative_url' => $this->fileSystem->url($thumbnailFilePath),
            ];
        }

        return $metaData ?? [];
    }

    /**
     * Get thumbnail image.
     *
     * @param string $originalFileContent
     * @param string $width
     * @param string $height
     * @param string $mode
     * @return ImageInterface
     */
    private function getThumbnailImage(string $originalFileContent, string $width, string $height, string $mode) : ImageInterface
    {
        return (new Imagine)->load($originalFileContent)->thumbnail(new Box($width, $height), $mode);
    }

    /**
     * Get thumbnail file path.
     *
     * @param string $originalFilePath
     * @param string $width
     * @param string $height
     * @return string
     */
    private function getThumbnailFilePath(string $originalFilePath, string $width, string $height) : string
    {
        return sprintf('%s/%s_%sx%s.%s',
            pathinfo($originalFilePath, PATHINFO_DIRNAME),
            pathinfo($originalFilePath, PATHINFO_FILENAME),
            $width,
            $height,
            pathinfo($originalFilePath, PATHINFO_EXTENSION));
    }

    /**
     * Get thumbnail extension.
     *
     * @param string $originalFilePath
     * @return string
     */
    private function getThumbnailExtension(string $originalFilePath) : string
    {
        return pathinfo($originalFilePath, PATHINFO_EXTENSION);
    }
}
