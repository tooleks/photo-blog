<?php

namespace Lib\DataProvider\Criterias;

use Lib\DataProvider\Contracts\Criteria;
use Lib\DataProvider\Criterias\Templates\SortBy;

/**
 * Class OrderByUpdatedAt.
 *
 * @package Lib\DataProvider\Criterias
 */
class SortByUpdatedAt extends SortBy implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->orderBy('updated_at', $this->order);
    }
}
