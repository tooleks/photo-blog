<?php

namespace Lib\DataProvider\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class Skip.
 *
 * @package Lib\DataProvider\Criterias
 */
class Skip implements Criteria
{
    /**
     * @var int
     */
    private $skip;

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
    public function apply($query): void
    {
        $query->skip($this->skip);
    }
}
