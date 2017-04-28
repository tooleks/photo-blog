<?php

namespace Lib\ExifFetcher;

use Lib\ExifFetcher\Contracts\ExifFetcher as ExifFetcherContract;
use Lib\ExifFetcher\Exception\ExifFetcherException;

/**
 * Class ExifFetcher.
 *
 * @package Lib\ExifFetcher
 */
class ExifFetcher implements ExifFetcherContract
{
    /**
     * @inheritdoc
     */
    public function fetch(string $filePath) : array
    {
       /*
        | Error control operator used for preventing the
        | "exif_read_data(...): IFD data bad offset: ... length ..." error.
        | In order to return at least some EXIF data.
        */
        $exif = @exif_read_data($filePath);

        return is_array($exif) ? $exif : [];
    }
}
