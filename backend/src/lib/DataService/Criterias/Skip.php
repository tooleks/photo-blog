<?php

namespace Lib\DataService\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class Skip.
 *
 * @property int skip
 * @package Lib\DataService\Criterias
 */
class Skip implements Criteria
{
    /**
     * Skip constructor.
     *
     * @param int $skip
     */
    public function __construct(int $skip)
    {
        $this->skip = $skip;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        return $query->skip($this->skip);
    }
}
