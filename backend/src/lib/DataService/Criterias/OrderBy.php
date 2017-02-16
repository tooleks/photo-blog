<?php

namespace Lib\DataService\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class OrderBy.
 *
 * @property string field
 * @property string direction
 * @package Lib\DataService\Criterias
 */
class OrderBy implements Criteria
{
    /**
     * OrderBy constructor.
     *
     * @param string $field
     * @param string $direction
     */
    public function __construct(string $field, string $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        return $query->orderBy($this->field, $this->direction);
    }
}
