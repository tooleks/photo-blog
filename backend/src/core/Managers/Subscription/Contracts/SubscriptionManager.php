<?php

namespace Core\Managers\Subscription\Contracts;

use Closure;
use Core\Models\Subscription;

/**
 * Interface SubscriptionManager.
 *
 * @package Core\Managers\Subscription\Contracts
 */
interface SubscriptionManager
{
    /**
     * Get the subscription by token.
     *
     * @param string $token
     * @return Subscription
     */
    public function getByToken(string $token): Subscription;

    /**
     * Apply the callback function on each subscription filtered by emails list.
     *
     * @param Closure $callback
     * @param array $emails
     * @return void
     */
    public function eachFilteredByEmails(Closure $callback, array $emails);

    /**
     * Generate the subscription by email.
     *
     * @param string $email
     * @return Subscription
     */
    public function generateByEmail(string $email): Subscription;

    /**
     * Delete the subscription.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function delete(Subscription $subscription);
}
