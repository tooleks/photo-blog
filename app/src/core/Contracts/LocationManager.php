<?php

namespace Core\Contracts;

use Core\Entities\LocationEntity;

/**
 * Interface LocationManager.
 *
 * @package Core\Contracts
 */
interface LocationManager
{
    /**
     * Create a location.
     *
     * @param array $attributes
     * @return LocationEntity
     */
    public function create(array $attributes): LocationEntity;
}
