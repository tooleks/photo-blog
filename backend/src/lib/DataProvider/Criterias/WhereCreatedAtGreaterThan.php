<?php

namespace Lib\DataProvider\Criterias;

use Carbon\Carbon;
use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereCreatedAtGreaterThan.
 *
 * @package Lib\DataProvider\Criterias
 */
class WhereCreatedAtGreaterThan implements Criteria
{
    /**
     * @var Carbon
     */
    private $date;

    /**
     * WhereUpdatedAtLessThan constructor.
     *
     * @param Carbon $date
     */
    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->where('created_at', '>', $this->date);
    }
}
