<?php

namespace Lib\Repositories\Contracts;

/**
 * Interface Criteria.
 *
 * @package Lib\Repositories\Contracts
 */
interface Criteria
{
    /**
     * Apply search query criteria.
     *
     * @param mixed $query
     * @return mixed
     */
    public function apply($query);
}
