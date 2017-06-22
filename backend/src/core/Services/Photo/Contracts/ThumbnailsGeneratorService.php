<?php

namespace Core\Services\Photo\Contracts;

use Core\Services\Contracts\Runnable;

/**
 * Interface ThumbnailsGeneratorService.
 *
 * @package Core\Services\Photo\Contracts
 */
interface ThumbnailsGeneratorService extends Runnable
{
    /**
     * Generate the photo thumbnails and return records data.
     *
     * @param array ...$parameters
     * @return array
     */
    public function run(...$parameters): array;
}
