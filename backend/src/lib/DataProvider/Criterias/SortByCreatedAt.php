<?php

namespace Lib\DataProvider\Criterias;

use Lib\DataProvider\Contracts\Criteria;
use Lib\DataProvider\Criterias\Templates\SortBy;

/**
 * Class SortByCreatedAt.
 *
 * @package Lib\DataProvider\Criterias
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
