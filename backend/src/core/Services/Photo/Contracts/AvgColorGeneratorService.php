<?php

namespace Core\Services\Photo\Contracts;

use Core\Services\Contracts\Runnable;

/**
 * Interface AvgColorGeneratorService.
 *
 * @package Core\Services\Photo\Contracts
 */
interface AvgColorGeneratorService extends Runnable
{
    /**
     * Generate the photo average color in a HEX format (#ffffff).
     *
     * @param array ...$parameters
     * @return string
     */
    public function run(...$parameters): string;
}
