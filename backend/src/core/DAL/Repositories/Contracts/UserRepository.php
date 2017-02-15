<?php

namespace Core\DAL\Repositories\Contracts;

use Core\DAL\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface UserRepository.
 *
 * @package Core\DAL\Repositories\Contracts
 */
interface UserRepository
{
    /**
     * Get user by credentials.
     *
     * @param string $email
     * @param string $password
     * @return User
     * @throws ModelNotFoundException
     */
    public function getUserByCredentials(string $email, string $password) : User;

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User
     */
    public function getUserById(int $id) : User;

    /**
     * Save a user.
     *
     * @param User $user
     * @param array $attributes
     * @return void
     */
    public function saveUser(User $user, array $attributes = []);

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user) : bool;
}
