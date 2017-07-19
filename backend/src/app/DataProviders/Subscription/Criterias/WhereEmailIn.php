<?php

namespace App\DataProviders\Subscription\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereEmailIn.
 *
 * @package App\DataProviders\Subscription\Criterias
 */
class WhereEmailIn implements Criteria
{
    /**
     * @var array
     */
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
    public function apply($query): void
    {
        $query->whereIn('subscriptions.email', $this->list);
    }
}
