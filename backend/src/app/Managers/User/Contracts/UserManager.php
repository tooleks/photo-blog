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
     * Get the user by ID.
     *
     * @param int $id
     * @return User
     */
    public function getById(int $id): User;

    /**
     * Get the user by name.
     *
     * @param string $name
     * @return User
     */
    public function getByName(string $name): User;

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
     * Create the user.
     *
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User;

    /**
     * Save the user.
     *
     * @param User $user
     * @param array $attributes
     * @return void
     */
    public function save(User $user, array $attributes): void;

    /**
     * Delete the user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void;
}
