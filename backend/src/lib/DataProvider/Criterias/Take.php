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
    /**
     * @var int
     */
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
    public function apply($query): void
    {
        $query->take($this->take);
    }
}
