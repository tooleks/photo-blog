<?php

namespace Core\DataProviders\Subscription\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereEmailIn.
 *
 * @property array list
 * @package Core\DataProviders\Subscription\Criterias
 */
class WhereEmailIn implements Criteria
{
    /**
     * WhereEmailIn constructor.
     *
     * @param array $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->whereIn('subscriptions.email', $this->list);
    }
}
