<?php

namespace Lib\ThumbnailsGenerator;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator as ThumbnailsGeneratorContract;
use Lib\ThumbnailsGenerator\Exceptions\ThumbnailsGeneratorException;

/**
 * Class ThumbnailsGenerator.
 *
 * @property Filesystem fileSystem
 * @property array thumbnailsConfig
 * @package Lib\ThumbnailsGenerator
 */
class ThumbnailsGenerator implements ThumbnailsGeneratorContract
{
    /**
     * ThumbnailsGenerator constructor.
     *
     * Example of initialization:
     * $thumbnailsConfig = [
     *     [
     *         'mode' => 'inset',
     *         'quality' => 100,    // percentage
     *         'width' => 1500,     // pixels
     *         'height' => 1500,    // pixels
     *     ],
     *     [
     *         'mode' => 'outbound',
     *         'quality' => 65,    // percentage
     *         'width' => 600,     // pixels
     *         'height' => 600,    // pixels
     *     ],
     * ];
     * new ThumbnailsGenerator($thumbnailsConfig);
     *
     * @param array $thumbnailsConfig
     */
    public function __construct(array $thumbnailsConfig)
    {
        $this->assertThumbnailsConfig($thumbnailsConfig);

        $this->thumbnailsConfig = $thumbnailsConfig;
    }

    /**
     * Assert thumbnails config.
     *
     * @param array $config
     * @throws ThumbnailsGeneratorException
     * @return void
     */
    private function assertThumbnailsConfig(array $config)
    {
        $validator = ValidatorFactory::make($config, [
            '*.mode' => ['required', Rule::in(['inset', 'outbound'])],
            '*.quality' => ['required', 'integer', 'min:0', 'max:100'],
            '*.width' => ['required', 'integer', 'min:1'],
            '*.height' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ThumbnailsGeneratorException(sprintf('Invalid configuration value while initializing "%s" class.', static::class));
        }
    }

    /**
     * @inheritdoc
     */
    public function generateThumbnails(string $originalImageFilePath): array
    {
        $metaData = [];

        $originalImage = (new Imagine)->open($originalImageFilePath);

        $this->eachThumbnailConfig(function ($config) use ($originalImage, &$metaData) {
            // Generate thumbnail image.
            $thumbnailImage = $originalImage->thumbnail(new Box($config['width'], $config['height']), $config['mode']);
            // Generate thumbnail file path.
            $thumbnailFilePath = $this->generateThumbnailFilePath(
                $originalImage->metadata()->get('filepath'),
                $thumbnailImage->getSize()->getWidth(),
                $thumbnailImage->getSize()->getHeight()
            );
            // Save thumbnail file.
            $thumbnailImage->save($thumbnailFilePath, ['quality' => $config['quality']]);
            // Append thumbnail metadata.
            $metaData[] = [
                'path' => $thumbnailFilePath,
                'width' => $thumbnailImage->getSize()->getWidth(),
                'height' => $thumbnailImage->getSize()->getHeight(),
            ];
        });

        return $metaData;
    }

    /**
     * Apply callback function on each thumbnail config.
     *
     * @param Closure $closure
     */
    private function eachThumbnailConfig(Closure $closure)
    {
        foreach ($this->thumbnailsConfig as $config) {
            $closure($config);
        }
    }

    /**
     * Generate thumbnail file path.
     *
     * @param string $originalImageFilePath
     * @param string $width
     * @param string $height
     * @return string
     */
    private function generateThumbnailFilePath(string $originalImageFilePath, string $width, string $height): string
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
