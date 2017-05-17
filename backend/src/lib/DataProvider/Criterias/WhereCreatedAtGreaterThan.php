<?php

namespace Lib\DataProvider\Criterias;

use Carbon\Carbon;
use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereCreatedAtGreaterThan.
 *
 * @property Carbon date
 * @package Lib\DataProvider\Criterias
 */
class WhereCreatedAtGreaterThan implements Criteria
{
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
