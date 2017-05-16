<?php

namespace Core\DataProviders\Subscription;

use Core\DataProviders\Subscription\Contracts\SubscriptionDataProvider as SubscriptionDataProviderContract;
use Lib\DataProvider\DataProvider;
use Lib\DataProvider\Exceptions\DataProviderNotFoundException;

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

    /**
     * @inheritdoc
     */
    public function getByToken($token, array $options = [])
    {
        $this->dispatchEvent('beforeGetByToken', $this->query, $options);

        $model = $this->query->where('token', $token)->first();

        if (is_null($model)) {
            throw new DataProviderNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        $this->dispatchEvent('afterGetByToken', $model, $options);

        $this->reset();

        return $model;
    }
}
