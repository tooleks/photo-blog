<?php

namespace App\DataProviders\Subscription\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereToken.
 *
 * @package App\DataProviders\Subscription\Criterias
 */
class WhereToken implements Criteria
{
    /**
     * @var string
     */
    private $token;

    /**
     * WhereToken constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @inheritdoc
     */
    public function apply($query): void
    {
        $query->where('subscriptions.token', $this->token);
    }
}
