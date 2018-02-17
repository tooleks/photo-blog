<?php

namespace Lib\ThumbnailsGenerator;

use Closure;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
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
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * @var array
     */
    private $config;

    /**
     * ThumbnailsGenerator constructor.
     *
     * Example of configuration:
     * $config = [
     *     [
     *         'mode' => 'inset',
     *         'quality' => 100,    // percentage
     *         'name' => 'large',   // name
     *         'width' => 1500,     // pixels
     *         'height' => 1500,    // pixels
     *     ],
     * ];
     *
     * @param ValidatorFactory $validatorFactory
     * @param array $config
     * @throws ThumbnailsGeneratorException
     */
    public function __construct(ValidatorFactory $validatorFactory, array $config)
    {
        $this->validatorFactory = $validatorFactory;

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
        $validator = $this->validatorFactory->make($config, [
            '*.mode' => ['required', Rule::in(['inset', 'outbound'])],
            '*.quality' => ['required', 'integer', 'min:0', 'max:100'],
            '*.name' => ['required', 'string', 'min:1'],
            '*.width' => ['required', 'integer', 'min:1'],
            '*.height' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ThumbnailsGeneratorException(sprintf('Invalid configuration of "%s".', static::class));
        }
    }

    /**
     * @inheritdoc
     */
    public function generateThumbnails(string $imagePath): array
    {
        $image = (new Imagine)->open($imagePath);

        $this->eachConfig(function ($config) use ($image, &$meta) {
            $thumbnail = $image->thumbnail(new Box($config['width'], $config['height']), $config['mode']);
            $thumbnailPath = $this->generateThumbnailPath($image->metadata()->get('filepath'), $config['name']);
            $thumbnail->save($thumbnailPath, ['quality' => $config['quality']]);

            $meta[] = [
                'path' => $thumbnailPath,
                'width' => $thumbnail->getSize()->getWidth(),
                'height' => $thumbnail->getSize()->getHeight(),
            ];
        });

        return $meta ?? [];
    }

    /**
     * Apply callback function on each thumbnail config.
     *
     * @param Closure $callback
     * @return void
     */
    private function eachConfig(Closure $callback): void
    {
        array_map($callback, $this->config);
    }

    /**
     * Generate thumbnail path.
     *
     * @param string $imagePath
     * @param string $fileSuffix
     * @return string
     */
    private function generateThumbnailPath(string $imagePath, string $fileSuffix = 'thumbnail'): string
    {
        $directoryName = pathinfo($imagePath, PATHINFO_DIRNAME);
        $fileName = pathinfo($imagePath, PATHINFO_FILENAME);
        $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

        return sprintf('%s/%s_%s.%s', $directoryName, $fileName, $fileSuffix, $fileExtension);
    }
}
