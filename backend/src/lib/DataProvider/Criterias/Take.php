<?php

namespace Lib\DataProvider\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class Take.
 *
 * @package Lib\DataProvider\Criterias
 */
class Take implements Criteria
{
    private $take;

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
        $query->take($this->take);
    }
}
