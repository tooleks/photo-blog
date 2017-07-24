<?php

namespace Lib\DataProvider\Eloquent\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereName.
 *
 * @package Lib\DataProvider\Eloquent\Criterias
 */
class WhereName implements Criteria
{
    /**
     * @var string
     */
    private $name;

    /**
     * WhereName constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function apply($query): void
    {
        $query->where('name', $this->name);
    }
}
