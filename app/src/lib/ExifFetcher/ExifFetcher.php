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
    public function fetch(string $imagePath): array
    {
        // Error control operator (@) is used in order to prevent
        // the "exif_read_data(...): IFD data bad offset: ... length ..." error.
        $exif = @exif_read_data($imagePath);

        if (is_array($exif)) {
            // Encode each node into UTF-8 encoding in order to prevent the
            // "Malformed UTF-8 characters, possibly incorrectly encoded." error.
            array_walk_recursive($exif, function (&$node) {
                if (is_string($node)) $node = utf8_encode($node);
            });
        }

        return is_array($exif) ? $exif : [];
    }
}
