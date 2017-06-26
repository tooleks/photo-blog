<?php

namespace Core\Managers\User\Contracts;

use Core\Models\User;

/**
 * Interface UserManager.
 *
 * @package Core\Managers\User\Contracts
 */
interface UserManager
{
    /**
     * Get the user by email.
     *
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User;

    /**
     * Get the user by credentials.
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    public function getByCredentials(string $email, string $password): User;

    /**
     * Re-generate the api token.
     *
     * @param User $user
     * @return void
     */
    public function reGenerateApiToken(User $user);
}
