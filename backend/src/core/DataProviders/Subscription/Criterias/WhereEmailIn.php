<?php

namespace Core\DataProviders\Subscription\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereEmailIn.
 *
 * @package Core\DataProviders\Subscription\Criterias
 */
class WhereEmailIn implements Criteria
{
    private $list;

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
