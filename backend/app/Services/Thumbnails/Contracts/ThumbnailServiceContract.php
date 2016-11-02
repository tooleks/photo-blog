<?php

namespace App\Services\Thumbnails\Contracts;

/**
 * Interface ThumbnailServiceContract
 * @package App\Services\Thumbnails\Contracts
 */
interface ThumbnailServiceContract
{
    /**
     * Create thumbnail file from given original file path.
     *
     * Note: Thumbnail file will be stored in the directory of the original file with the size suffix.
     *
     * @param string $originalFilePath
     * @param string $width
     * @param string $height
     * @return string
     */
    public function createThumbnail(string $originalFilePath, string $width, string $height): string;

    /**
     * Generate thumbnail file content.
     *
     * @param string $originalContent
     * @param string $width
     * @param string $height
     * @return string
     */
    public function generateThumbnailFileContent(string $originalContent, string $width, string $height) : string;
}
