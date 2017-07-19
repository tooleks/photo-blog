<?php

namespace App\Services\Photo\Contracts;

/**
 * Interface ThumbnailsGeneratorService.
 *
 * @package App\Services\Photo\Contracts
 */
interface ThumbnailsGeneratorService
{
    /**
     * Generate the photo thumbnails by the file path and returns the thumbnails metadata.
     *
     * @param string $filePath
     * @return array
     */
    public function generateByFilePath(string $filePath): array;
}
