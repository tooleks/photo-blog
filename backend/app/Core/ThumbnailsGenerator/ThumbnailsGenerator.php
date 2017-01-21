<?php

namespace App\Core\ThumbnailsGenerator;

use App\Core\ThumbnailsGenerator\Contracts\ThumbnailsGeneratorContract;
use App\Core\ThumbnailsGenerator\Exceptions\ThumbnailException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;

/**
 * Class ThumbnailsGenerator.
 *
 * @property Filesystem fs
 * @property array config
 * @package App\Core\ThumbnailsGenerator
 */
class ThumbnailsGenerator implements ThumbnailsGeneratorContract
{
    const THUMBNAIL_MODE_INSET = 'inset';
    const THUMBNAIL_MODE_OUTBOUND = 'outbound';

    /**
     * ThumbnailsGenerator constructor.
     *
     * @param Filesystem $fs
     * @param array $config
     */
    public function __construct(Filesystem $fs, array $config)
    {
        $this->fs = $fs;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function generateThumbnails(string $originalFilePath) : array
    {
        $originalFileContent = $this->fs->get($originalFilePath);

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

            if (!$this->fs->put($thumbnailFilePath, $thumbnailImage->get($this->getThumbnailExtension($originalFilePath)))) {
                throw new ThumbnailException(sprintf('An error occurred while saving thumbnail file "%s".', $thumbnailFilePath));
            }

            $metaData[] = [
                'width' => $thumbnailImage->getSize()->getWidth(),
                'height' => $thumbnailImage->getSize()->getHeight(),
                'path' => $thumbnailFilePath,
                'relative_url' => $this->fs->url($thumbnailFilePath),
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
