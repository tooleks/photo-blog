<?php

namespace Core\Services\Photo\Contracts;

use Core\Services\Contracts\Runnable;

/**
 * Interface ExifFetcherService.
 *
 * @package Core\Services\Photo\Contracts
 */
interface ExifFetcherService extends Runnable
{
    /**
     * Fetch the photo exif values and return record data.
     *
     * @param array ...$parameters
     * @return array
     */
    public function run(...$parameters): array;
}
