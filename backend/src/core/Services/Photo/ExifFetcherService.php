<?php

namespace Core\Services\Photo;

use Core\Services\Photo\Contracts\ExifFetcherService as ExifFetcherServiceContract;
use Lib\ExifFetcher\Contracts\ExifFetcher;

/**
 * Class ExifFetcherService.
 *
 * @property ExifFetcher exifFetcher
 * @package Core\Services\Photo
 */
class ExifFetcherService implements ExifFetcherServiceContract
{
    /**
     * ExifFetcherService constructor.
     *
     * @param ExifFetcher $exifFetcher
     */
    public function __construct(ExifFetcher $exifFetcher)
    {
        $this->exifFetcher = $exifFetcher;
    }

    /**
     * @inheritdoc
     */
    public function run(...$parameters)
    {
        list($file) = $parameters;

        $exif = $this->exifFetcher->fetch($file->getPathname());

        // Replace the temporary file name with the original one.
        $exif['FileName'] = $file->getClientOriginalName();

        return ['data' => $exif];
    }
}
