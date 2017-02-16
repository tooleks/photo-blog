<?php

namespace Lib\DataService\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class OrderByCreatedAt.
 *
 * @property string direction
 * @package Lib\DataService\Criterias
 */
class OrderByCreatedAt implements Criteria
{
    /**
     * OrderByCreatedAt constructor.
     *
     * @param string $direction
     */
    public function __construct(string $direction)
    {
        $this->direction = $direction;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        return $query->orderBy('created_at', $this->direction);
    }
}
