<?php

namespace Lib\ExifFetcher;

use Lib\ExifFetcher\Contracts\ExifFetcher as ExifFetcherContract;

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
    public function fetch(string $filePath): array
    {
        /*
         | Error control operator used in order to prevent the
         | "exif_read_data(...): IFD data bad offset: ... length ..." error.
         | In order to return at least some EXIF data.
         */
        $rawExif = @exif_read_data($filePath);

        if (is_array($rawExif)) {
            // Encode each node into UTF-8 encoding in order to prevent the
            // "Malformed UTF-8 characters, possibly incorrectly encoded." error.
            array_walk_recursive($rawExif, function (&$node) {
                if (is_string($node)) {
                    $node = utf8_encode($node);
                }
            });
        }

        return is_array($rawExif) ? $rawExif : [];
    }
}
