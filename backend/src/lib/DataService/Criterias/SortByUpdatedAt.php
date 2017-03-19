<?php

namespace Lib\DataService\Criterias;

use Lib\DataService\Contracts\Criteria;
use Lib\DataService\Criterias\Templates\SortBy;

/**
 * Class OrderByUpdatedAt.
 *
 * @package Lib\DataService\Criterias
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
