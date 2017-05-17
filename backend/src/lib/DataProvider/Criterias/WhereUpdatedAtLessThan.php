<?php

namespace Lib\DataProvider\Criterias;

use Carbon\Carbon;
use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereUpdatedAtLessThan.
 *
 * @property Carbon date
 * @package Lib\DataProvider\Criterias
 */
class WhereUpdatedAtLessThan implements Criteria
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
        $query->where('updated_at', '<', $this->date);
    }
}
