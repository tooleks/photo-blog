<?php

namespace App\Services\Image;

use App\Services\Image\Contracts\ImageProcessor;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface as Imagine;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Metadata\ExifMetadataReader;
use Imagine\Image\Point;
use InvalidArgumentException;

/**
 * Class ImagineImageProcessor.
 *
 * @package App\Services\Image
 */
class ImagineImageProcessor implements ImageProcessor
{
    /**
     * @var Imagine
     */
    private $imagine;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ImageInterface|null
     */
    private $image = null;

    /**
     * @var string|null
     */
    private $path = null;

    /**
     * ImagineImageProcessor constructor.
     *
     * @param Imagine $imagine
     * @param Storage $storage
     * @param ValidatorFactory $validatorFactory
     * @param array $config
     */
    public function __construct(Imagine $imagine, Storage $storage, ValidatorFactory $validatorFactory, array $config)
    {
        $validator = $validatorFactory->make($config, [
            'thumbnails' => ['required', 'array'],
            'thumbnails.*.mode' => ['required', Rule::in([ManipulatorInterface::THUMBNAIL_INSET, ManipulatorInterface::THUMBNAIL_OUTBOUND])],
            'thumbnails.*.quality' => ['required', 'integer', 'min:0', 'max:100'],
            'thumbnails.*.prefix' => ['required', 'string', 'min:1'],
            'thumbnails.*.width' => ['required', 'integer', 'min:1'],
            'thumbnails.*.height' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException('Invalid configuration has been provided.');
        }

        $this->imagine = $imagine->setMetadataReader(new ExifMetadataReader);
        $this->storage = $storage;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function open(string $path): ImageProcessor
    {
        $this->close();

        $this->path = $path;
        $this->image = $this->imagine->open($this->storage->path($path));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function close(): ImageProcessor
    {
        $this->path = null;
        $this->image = null;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAvgColor(): string
    {
        return (string) $this->image
            ->copy()
            ->resize(new Box(1, 1))
            ->getColorAt(new Point(0, 0));
    }

    /**
     * @inheritdoc
     */
    public function getMetadata(): array
    {
        return $this->image
            ->metadata()
            ->toArray();
    }

    /**
     * @inheritdoc
     */
    public function createThumbnails(): array
    {
        return collect($this->config['thumbnails'])
            ->map(function ($config) {
                $thumbnail = $this->image
                    ->thumbnail(new Box($config['width'], $config['height']), $config['mode'])
                    ->save($this->getThumbnailStoragePath($config['prefix']), ['quality' => $config['quality']]);

                return [
                    'path' => $this->getThumbnailAbsolutePath($config['prefix']),
                    'width' => $thumbnail->getSize()->getWidth(),
                    'height' => $thumbnail->getSize()->getHeight(),
                ];
            })
            ->toArray();
    }

    /**
     * Get storage path to the thumbnail file.
     *
     * @param string $prefix
     * @return string
     */
    private function getThumbnailStoragePath(string $prefix): string
    {
        return pathinfo($this->storage->path($this->path), PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $this->getThumbnailName($prefix);
    }

    /**
     * Get fully specified path to the thumbnail file.
     *
     * @param string $prefix
     * @return string
     */
    private function getThumbnailAbsolutePath(string $prefix): string
    {
        return pathinfo($this->path, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $this->getThumbnailName($prefix);
    }

    /**
     * Get thumbnail file name.
     *
     * @param string $prefix
     * @return string
     */
    private function getThumbnailName(string $prefix): string
    {
        return pathinfo($this->storage->path($this->path), PATHINFO_FILENAME) . '_' . $prefix . '.' . pathinfo($this->storage->path($this->path), PATHINFO_EXTENSION);
    }
}
