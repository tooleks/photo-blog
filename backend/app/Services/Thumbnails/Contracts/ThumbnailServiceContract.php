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
     * @param string $type
     * @return string
     */
    public function createThumbnailFile(
        string $originalFilePath,
        string $width,
        string $height,
        string $type
    ): string;

    /**
     * Generate thumbnail file content.
     *
     * @param string $originalContent
     * @param string $format
     * @param string $width
     * @param string $height
     * @param string $type
     * @return string
     */
    public function generateThumbnailFileContent(
        string $originalContent,
        string $format,
        string $width,
        string $height,
        string $type
    ) : string;
}
