<?php

namespace Core\Services\Contracts;

/**
 * Interface Revertable.
 *
 * @package Core\Services\Contracts
 */
interface Revertable
{
    /**
     * Revert the service action.
     *
     * @param array $parameters
     * @return mixed
     */
    public function run(...$parameters);
}
