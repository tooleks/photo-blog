<?php

namespace Core\Managers\Subscription;

use Closure;
use Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider;
use Core\DataProviders\Subscription\Criterias\WhereEmailIn;
use Core\DataProviders\Subscription\Criterias\WhereToken;
use Core\Managers\Subscription\Contracts\SubscriptionManager as SubscriptionManagerContract;
use Core\Models\Subscription;

/**
 * Class SubscriptionManager.
 *
 * @package Core\Managers\Subscription
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
    public function eachFilteredByEmails(Closure $callback, array $emails)
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
    public function delete(Subscription $subscription)
    {
        $this->subscriptionDataProvider->delete($subscription);
    }
}
