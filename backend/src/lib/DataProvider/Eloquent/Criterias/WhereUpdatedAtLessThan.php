<?php

namespace Lib\DataProvider\Eloquent\Criterias;

use Carbon\Carbon;
use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereUpdatedAtLessThan.
 *
 * @package Lib\DataProvider\Eloquent\Criterias
 */
class WhereUpdatedAtLessThan implements Criteria
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
    public function apply($query): void
    {
        $query->where('updated_at', '<', $this->date);
    }
}
