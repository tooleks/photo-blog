<?php

namespace Core\DataProviders\Subscription\Contracts;

use Lib\DataProvider\Contracts\DataProvider;

/**
 * Interface SubscriptionDataProvider.
 *
 * @package Core\DataProviders\Subscription\Contracts
 */
interface SubscriptionDataProvider extends DataProvider
{
    /**
     * Get model by token.
     *
     * @param string $token
     * @param array $options
     * @return mixed
     */
    public function getByToken($token, array $options = []);
}
