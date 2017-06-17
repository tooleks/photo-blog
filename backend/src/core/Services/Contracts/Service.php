<?php

namespace Core\Services\Contracts;

/**
 * Interface Service.
 *
 * @package Core\Services\Contracts
 */
interface Service
{
    /**
     * Run the service.
     *
     * @param array $parameters
     * @return mixed
     */
    public function run(...$parameters);
}
