<?php

namespace App\Models\Builders;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Class SubscriptionBuilder.
 *
 * @package App\Models\Builders
 */
class SubscriptionBuilder extends Builder
{
    /**
     * @var string
     */
    private $subscriptionsTable;

    /**
     * SubscriptionBuilder constructor.
     *
     * @param QueryBuilder $query
     */
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);

        $this->subscriptionsTable = (new Subscription)->getTable();
    }

    /**
     * @param string $ids
     * @return $this
     */
    public function whereIds(string $ids)
    {
        return $this->whereIn("{$this->subscriptionsTable}.id", explode(',', $ids));
    }

    /**
     * @param string $email
     * @return $this
     */
    public function whereEmailLike(string $email)
    {
        return $this->where("{$this->subscriptionsTable}.email", 'like', $email);
    }

    /**
     * @param array $emails
     * @return $this
     */
    public function whereEmailIn(array $emails)
    {
        return $this->whereIn("{$this->subscriptionsTable}.email", $emails);
    }

    /**
     * @param string $token
     * @return $this
     */
    public function whereTokenEquals(string $token)
    {
        return $this->where("{$this->subscriptionsTable}.token", $token);
    }
}
