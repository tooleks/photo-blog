<?php

namespace Lib\ExifFetcher\Contracts;

use Lib\ExifFetcher\Exception\ExifFetcherException;

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
     * @throws ExifFetcherException
     */
    public function fetch(string $filePath) : array;
}
