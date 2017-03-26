<?php

namespace Lib\DataProvider\Contracts;

/**
 * Interface Criteria.
 *
 * @package Lib\DataProvider\Contracts
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
