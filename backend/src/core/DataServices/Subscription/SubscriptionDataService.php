<?php

namespace Core\DataServices\Subscription;

use Core\DataServices\Subscription\Contracts\SubscriptionDataService as SubscriptionDataServiceContract;
use Lib\DataService\DataService;

/**
 * Class SubscriptionDataService.
 *
 * @package Core\DataServices
 */
class SubscriptionDataService extends DataService implements SubscriptionDataServiceContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\Models\Subscription::class;
    }
}
