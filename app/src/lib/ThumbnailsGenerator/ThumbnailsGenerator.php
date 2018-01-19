<?php

namespace Lib\ThumbnailsGenerator;

use Closure;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator as ThumbnailsGeneratorContract;
use Lib\ThumbnailsGenerator\Exceptions\ThumbnailsGeneratorException;

/**
 * Class ThumbnailsGenerator.
 *
 * @package Lib\ThumbnailsGenerator
 */
class ThumbnailsGenerator implements ThumbnailsGeneratorContract
{
    /**
     * @var array
     */
    private $config;

    /**
     * ThumbnailsGenerator constructor.
     *
     * Example of initialization:
     * $config = [
     *     [
     *         'mode' => 'inset',
     *         'quality' => 100,    // percentage
     *         'name' => 'large',   // name
     *         'width' => 1500,     // pixels
     *         'height' => 1500,    // pixels
     *     ],
     * ];
     * $thumbnailsGenerator = new ThumbnailsGenerator($config);
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->assertConfig($config);

        $this->config = $config;
    }

    /**
     * Assert config.
     *
     * @param array $config
     * @throws ThumbnailsGeneratorException
     * @return void
     */
    private function assertConfig(array $config): void
    {
        $validator = ValidatorFactory::make($config, [
            '*.mode' => ['required', Rule::in(['inset', 'outbound'])],
            '*.quality' => ['required', 'integer', 'min:0', 'max:100'],
            '*.name' => ['required', 'string', 'min:1'],
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
    public function generateThumbnails(string $originalImageAbsPath): array
    {
        $originalImage = (new Imagine)->open($originalImageAbsPath);

        $this->eachThumbnailConfig(function ($config) use ($originalImage, &$metaData) {
            // Generate thumbnail image.
            $thumbnailImage = $originalImage->thumbnail(new Box($config['width'], $config['height']), $config['mode']);
            // Generate thumbnail file path.
            $thumbnailImageAbsPath = $this->generateThumbnailImageAbsPath(
                $originalImage->metadata()->get('filepath'), $config['name']
            );
            // Save thumbnail file.
            $thumbnailImage->save($thumbnailImageAbsPath, ['quality' => $config['quality']]);
            // Store thumbnail metadata.
            $metaData[] = [
                'path' => $thumbnailImageAbsPath,
                'width' => $thumbnailImage->getSize()->getWidth(),
                'height' => $thumbnailImage->getSize()->getHeight(),
            ];
        });

        return $metaData ?? [];
    }

    /**
     * Apply callback function on each thumbnail config.
     *
     * @param Closure $callback
     * @return void
     */
    private function eachThumbnailConfig(Closure $callback): void
    {
        array_map($callback, $this->config);
    }

    /**
     * Generate thumbnail image absolute path.
     *
     * @param string $originalImageAbsPath
     * @param string $suffix
     * @return string
     */
    private function generateThumbnailImageAbsPath(string $originalImageAbsPath, string $suffix = 'thumbnail'): string
    {
        return sprintf(
            '%s/%s_%s.%s',
            pathinfo($originalImageAbsPath, PATHINFO_DIRNAME),     // Subdirectory.
            pathinfo($originalImageAbsPath, PATHINFO_FILENAME),    // File name.
            $suffix,                                                        // Suffix.
            pathinfo($originalImageAbsPath, PATHINFO_EXTENSION));  // Extension.
    }
}
