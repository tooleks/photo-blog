<?php

namespace Lib\DataProvider\Eloquent\Criterias;

use Lib\DataProvider\Contracts\Criteria;
use Lib\DataProvider\Eloquent\Criterias\Templates\SortBy;

/**
 * Class OrderByUpdatedAt.
 *
 * @package Lib\DataProvider\Eloquent\Criterias
 */
class SortByUpdatedAt extends SortBy implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query): void
    {
        $query->orderBy('updated_at', $this->order);
    }
}
