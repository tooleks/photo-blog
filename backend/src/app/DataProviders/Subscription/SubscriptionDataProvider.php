<?php

namespace App\DataProviders\Subscription;

use App\DataProviders\Subscription\Contracts\SubscriptionDataProvider as SubscriptionDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class SubscriptionDataProvider.
 *
 * @package App\DataProviders
 */
class SubscriptionDataProvider extends DataProvider implements SubscriptionDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\Subscription::class;
    }
}
