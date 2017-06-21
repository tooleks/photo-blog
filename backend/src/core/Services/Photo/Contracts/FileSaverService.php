<?php

namespace Core\Services\Photo\Contracts;

use Core\Services\Contracts\Runnable;

/**
 * Interface FileSaverService.
 *
 * @package Core\Services\Photo\Contracts
 */
interface FileSaverService extends Runnable
{
    /**
     * Save the uploaded photo file and return it's path.
     *
     * @param array ...$parameters
     * @return string
     */
    public function run(...$parameters): string;
}
