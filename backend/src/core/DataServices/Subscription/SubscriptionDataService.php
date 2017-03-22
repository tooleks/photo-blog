<?php

namespace Core\DataServices\Subscription;

use Core\DataServices\Subscription\Contracts\SubscriptionDataService as SubscriptionDataServiceContract;
use Lib\DataService\DataService;
use Lib\DataService\Exceptions\DataServiceNotFoundException;

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

    /**
     * @inheritdoc
     */
    public function getByToken($token, array $options = [])
    {
        $this->dispatchEvent('beforeGetByToken', $this->query, $options);

        $model = $this->query->where('token', $token)->first();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        $this->dispatchEvent('afterGetByToken', $model, $options);

        $this->reset();

        return $model;
    }
}
