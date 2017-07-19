<?php

namespace App\Services\Photo;

use App\Services\Photo\Contracts\ExifFetcherService as ExifFetcherServiceContract;
use Illuminate\Http\UploadedFile;
use Lib\ExifFetcher\Contracts\ExifFetcher;

/**
 * Class ExifFetcherService.
 *
 * @package App\Services\Photo
 */
class ExifFetcherService implements ExifFetcherServiceContract
{
    /**
     * @var ExifFetcher
     */
    private $exifFetcher;

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
    public function fetchFromUploadedFile(UploadedFile $file): array
    {
        $data = $this->exifFetcher->fetch($file->getPathname());

        // Replace the temporary file name with the original one.
        $data['FileName'] = $file->getClientOriginalName();

        return ['data' => $data];
    }
}
