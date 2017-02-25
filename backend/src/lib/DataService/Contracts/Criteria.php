<?php

namespace Lib\DataService\Contracts;

/**
 * Interface Criteria.
 *
 * @package Lib\DataService\Contracts
 */
interface Criteria
{
    /**
     * Apply search query criteria.
     *
     * @param mixed $query
     * @return void
     */
    public function apply($query);
}
