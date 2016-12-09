<?php

namespace App\Core\ThumbnailsGenerator\Contracts;

/**
 * Interface ThumbnailServiceContract
 * @package App\Core\ThumbnailsGenerator\Contracts
 */
interface ThumbnailsGeneratorContract
{
    /**
     * Generate thumbnail files for original image file and return thumbnails metadata.
     *
     * @param string $originalFilePath
     * @return array
     */
    public function generateThumbnails(string $originalFilePath) : array;
}
