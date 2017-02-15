<?php

namespace Lib\Repositories\Criterias;

use Lib\Repositories\Contracts\Criteria;

/**
 * Class Take.
 *
 * @property int take
 * @package Lib\Repositories\Criterias
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
