<?php

namespace Lib\DataProvider\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class WhereEmail.
 *
 * @package Lib\DataProvider\Criterias
 */
class WhereEmail implements Criteria
{
    /**
     * @var string
     */
    private $email;

    /**
     * WhereEmail constructor.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->where('email', $this->email);
    }
}
