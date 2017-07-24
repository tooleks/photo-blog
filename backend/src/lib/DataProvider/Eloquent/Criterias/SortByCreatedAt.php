<?php

namespace Lib\DataProvider\Eloquent\Criterias;

use Lib\DataProvider\Contracts\Criteria;
use Lib\DataProvider\Eloquent\Criterias\Templates\SortBy;

/**
 * Class SortByCreatedAt.
 *
 * @package Lib\DataProvider\Eloquent\Criterias
 */
class SortByCreatedAt extends SortBy implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query): void
    {
        $query->orderBy('created_at', $this->order);
    }
}
