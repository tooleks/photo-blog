<?php

namespace App\DataProviders\Subscription;

use App\DataProviders\Subscription\Contracts\SubscriptionDataProvider as SubscriptionDataProviderContract;
use Lib\DataProvider\Eloquent\EloquentDataProvider;

/**
 * Class SubscriptionDataProvider.
 *
 * @package App\DataProviders
 */
class SubscriptionDataProvider extends EloquentDataProvider implements SubscriptionDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\Subscription::class;
    }
}
