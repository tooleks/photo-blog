<?php

namespace Core\DataProviders\Subscription\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereToken.
 *
 * @package Core\DataProviders\Subscription\Criterias
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
    public function apply($query)
    {
        $query->where('token', $this->token);
    }
}
