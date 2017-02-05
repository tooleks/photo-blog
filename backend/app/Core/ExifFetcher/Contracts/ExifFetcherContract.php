<?php

namespace App\Core\ExifFetcher\Contracts;

use App\Core\ExifFetcher\Exception\ExifFetcherException;

/**
 * Interface ExifFetcherContract.
 *
 * @package App\Core\ExifFetcher\Contracts
 */
interface ExifFetcherContract
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
