<?php

namespace Core\Services\Contracts;

/**
 * Interface Runnable.
 *
 * @package Core\Services\Contracts
 */
interface Runnable
{
    /**
     * Run the service action.
     *
     * @param array $parameters
     * @return mixed
     */
    public function run(...$parameters);
}
