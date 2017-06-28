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
