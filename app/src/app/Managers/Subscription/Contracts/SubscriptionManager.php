<?php

namespace App\Managers\Subscription\Contracts;

use Closure;
use App\Models\Subscription;

/**
 * Interface SubscriptionManager.
 *
 * @package App\Managers\Subscription\Contracts
 */
interface SubscriptionManager
{
    /**
     * Create a subscription by email.
     *
     * @param array $attributes
     * @return Subscription
     */
    public function create(array $attributes): Subscription;

    /**
     * Get a subscription by token.
     *
     * @param string $token
     * @return Subscription
     */
    public function getByToken(string $token): Subscription;

    /**
     * Paginate over subscriptions.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = []);

    /**
     * Delete a subscription.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function delete(Subscription $subscription): void;
}
