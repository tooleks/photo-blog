<?php

namespace App\Services\Photo\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Interface ExifFetcherService.
 *
 * @package App\Services\Photo\Contracts
 */
interface ExifFetcherService
{
    /**
     * Fetch the photo exif data values from the uploaded file.
     *
     * @param UploadedFile $file
     * @return array
     */
    public function fetch(UploadedFile $file): array;
}
