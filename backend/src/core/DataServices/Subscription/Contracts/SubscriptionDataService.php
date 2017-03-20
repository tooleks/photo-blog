<?php

namespace Core\DataServices\Subscription\Contracts;

use Lib\DataService\Contracts\DataService;

/**
 * Interface SubscriptionDataService.
 *
 * @package Core\DataServices\Subscription\Contracts
 */
interface SubscriptionDataService extends DataService
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
