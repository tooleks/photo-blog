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
     * Generate thumbnail files for original image file and return thumbnails metadata.
     *
     * @param string $originalFilePath
     * @return array
     */
    public function generateThumbnails(string $originalFilePath) : array;
}
