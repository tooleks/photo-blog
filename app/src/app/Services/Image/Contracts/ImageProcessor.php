<?php

namespace App\Services\Image\Contracts;

/**
 * Interface ImageProcessor.
 *
 * @package App\Services\Image\Contracts
 */
interface ImageProcessor
{
    /**
     * Open image processing session.
     *
     * @param string $path
     * @return ImageProcessor
     */
    public function open(string $path): ImageProcessor;

    /**
     * Close image processing session.
     *
     * @return ImageProcessor
     */
    public function close(): ImageProcessor;

    /**
     * Get the image average color in HEX format.
     *
     * @return string
     */
    public function getAvgColor(): string;

    /**
     * Get the image metadata.
     *
     * @return array
     */
    public function getMetadata(): array;

    /**
     * Create the image thumbnails.
     *
     * @return array
     */
    public function createThumbnails(): array;
}
