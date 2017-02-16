<?php

namespace Lib\DataService\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class Take.
 *
 * @property int take
 * @package Lib\DataService\Criterias
 */
class Take implements Criteria
{
    /**
     * Take constructor.
     *
     * @param int $take
     */
    public function __construct(int $take)
    {
        $this->take = $take;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        return $query->take($this->take);
    }
}
