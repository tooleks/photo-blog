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
     * Fetch EXIF data by a given file path.
     *
     * @param string $filePath
     * @return array
     */
    public function fetch(string $filePath): array;
}
