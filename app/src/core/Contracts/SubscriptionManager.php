<?php

namespace Core\Contracts;

use Core\Entities\SubscriptionEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface SubscriptionManager.
 *
 * @package Core\Contracts
 */
interface SubscriptionManager
{
    /**
     * Create a subscription by email.
     *
     * @param array $attributes
     * @return SubscriptionEntity
     */
    public function create(array $attributes): SubscriptionEntity;

    /**
     * Get the subscription by token.
     *
     * @param string $token
     * @return SubscriptionEntity
     */
    public function getByToken(string $token): SubscriptionEntity;

    /**
     * Paginate over subscriptions.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator;

    /**
     * Delete the subscription by token.
     *
     * @param string $token
     * @return SubscriptionEntity
     */
    public function deleteByToken(string $token): SubscriptionEntity;
}
