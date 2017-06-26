<?php

namespace Core\Services\Photo\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Interface ExifFetcherService.
 *
 * @package Core\Services\Photo\Contracts
 */
interface ExifFetcherService
{
    /**
     * Fetch the photo exif data values from the uploaded file.
     *
     * @param UploadedFile $file
     * @return array
     */
    public function fetchFromUploadedFile(UploadedFile $file): array;
}
