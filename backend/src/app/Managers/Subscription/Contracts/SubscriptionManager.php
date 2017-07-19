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
    public function eachFilteredByEmails(Closure $callback, array $emails): void;

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
    public function delete(Subscription $subscription): void;
}
