<?php

namespace Lib\ExifFetcher\Contracts;

/**
 * Interface ExifFetcher.
 *
 * @package Lib\ExifFetcher\Contracts
 */
interface ExifFetcher
{
    /**
     * Fetch EXIF data for the image.
     *
     * @param string $imagePath
     * @return array
     */
    public function fetch(string $imagePath): array;
}
