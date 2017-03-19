<?php

namespace Lib\DataService\Criterias;

use Lib\DataService\Contracts\Criteria;
use Lib\DataService\Criterias\Templates\SortBy;

/**
 * Class SortByCreatedAt.
 *
 * @package Lib\DataService\Criterias
 */
class SortByCreatedAt extends SortBy implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->orderBy('created_at', $this->order);
    }
}
