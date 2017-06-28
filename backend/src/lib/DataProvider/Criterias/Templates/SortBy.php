<?php

namespace Lib\DataProvider\Criterias\Templates;

/**
 * Class SortBy.
 *
 * @package Lib\DataProvider\Criterias\Templates
 */
abstract class SortBy
{
    protected const ORDER_ASC = 'asc';
    protected const ORDER_DESC = 'desc';

    /**
     * Sorting order.
     *
     * Default order: ascending.
     *
     * @var string
     */
    protected $order = self::ORDER_ASC;

    /**
     * Sort by descending order.
     *
     * @return $this
     */
    public function desc()
    {
        $this->order = self::ORDER_DESC;

        return $this;
    }

    /**
     * Sort by ascending order.
     *
     * @return $this
     */
    public function asc()
    {
        $this->order = self::ORDER_ASC;

        return $this;
    }
}
