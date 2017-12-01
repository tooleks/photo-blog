<?php

namespace App\Managers\User\Contracts;

use App\Models\User;

/**
 * Interface UserManager.
 *
 * @package App\Managers\User\Contracts
 */
interface UserManager
{
    /**
     * Create a user.
     *
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User;

    /**
     * Update a user.
     *
     * @param User $user
     * @param array $attributes
     * @return void
     */
    public function update(User $user, array $attributes): void;

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User
     */
    public function getById(int $id): User;

    /**
     * Get a user by name.
     *
     * @param string $name
     * @return User
     */
    public function getByName(string $name): User;

    /**
     * Get a user by email.
     *
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User;

    /**
     * Get a user by credentials.
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    public function getByCredentials(string $email, string $password): User;

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void;
}
