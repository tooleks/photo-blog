<?php

namespace App\Managers\Subscription;

use Closure;
use App\DataProviders\Subscription\Contracts\SubscriptionDataProvider;
use App\DataProviders\Subscription\Criterias\WhereEmailIn;
use App\DataProviders\Subscription\Criterias\WhereToken;
use App\Managers\Subscription\Contracts\SubscriptionManager as SubscriptionManagerContract;
use App\Models\Subscription;

/**
 * Class SubscriptionManager.
 *
 * @package App\Managers\Subscription
 */
class SubscriptionManager implements SubscriptionManagerContract
{
    /**
     * @var SubscriptionDataProvider
     */
    private $subscriptionDataProvider;

    /**
     * SubscriptionManager constructor.
     *
     * @param SubscriptionDataProvider $subscriptionDataProvider
     */
    public function __construct(SubscriptionDataProvider $subscriptionDataProvider)
    {
        $this->subscriptionDataProvider = $subscriptionDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function getByToken(string $token): Subscription
    {
        return $this->subscriptionDataProvider
            ->applyCriteria(new WhereToken($token))
            ->getFirst();
    }

    /**
     * @inheritdoc
     */
    public function eachFilteredByEmails(Closure $callback, array $emails): void
    {
        $this->subscriptionDataProvider
            ->applyCriteriaWhen((bool) $emails, new WhereEmailIn($emails))
            ->each($closure);
    }

    /**
     * @inheritdoc
     */
    public function generateByEmail(string $email): Subscription
    {
        $subscription = new Subscription(compact('email'));

        $subscription->token = str_random(64);

        $this->subscriptionDataProvider->save($subscription);

        return $subscription;
    }

    /**
     * @inheritdoc
     */
    public function delete(Subscription $subscription): void
    {
        $this->subscriptionDataProvider->delete($subscription);
    }
}
