<?php

namespace Lib\ThumbnailsGenerator\Contracts;

/**
 * Interface ThumbnailsGenerator.
 *
 * @package Lib\ThumbnailsGenerator\Contracts
 */
interface ThumbnailsGenerator
{
    /**
     * Generate thumbnails for the image and return thumbnails meta data.
     *
     * @param string $imagePath
     * @return array
     */
    public function generateThumbnails(string $imagePath): array;
}
