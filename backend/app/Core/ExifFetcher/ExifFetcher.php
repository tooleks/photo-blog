<?php

namespace App\Core\ExifFetcher;

use App\Core\ExifFetcher\Contracts\ExifContract;
use App\Core\ExifFetcher\Contracts\ExifFetcherContract;
use App\Core\ExifFetcher\Exception\ExifFetcherException;

/**
 * Class ExifFetcher.
 *
 * @package App\Core\ExifFetcher
 */
class ExifFetcher implements ExifFetcherContract
{
    /**
     * @inheritdoc
     */
    public function fetch(string $filePath) : array
    {
        if (!file_exists($filePath)) {
            throw new ExifFetcherException(sprintf('File "%s" does not exist.', $filePath));
        }

        if (!is_readable($filePath)) {
            throw new ExifFetcherException(sprintf('File "%s" is not readable.', $filePath));
        }

        /*
         | Error control operator used for preventing the
         | "exif_read_data(...): IFD data bad offset: ... length ..." error.
         | In order to return at least some EXIF data.
         */
        $exif = @exif_read_data($filePath);

        return $exif ? $exif : [];
    }
}
