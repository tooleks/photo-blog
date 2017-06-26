<?php

namespace Core\DataProviders\Subscription;

use Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider as SubscriptionDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class SubscriptionDataProvider.
 *
 * @package Core\DataProviders
 */
class SubscriptionDataProvider extends DataProvider implements SubscriptionDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \Core\Models\Subscription::class;
    }
}
