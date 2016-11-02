<?php

namespace App\Services\Users\Contracts;

use App\Models\User;
use App\Services\Users\Contracts\UserExceptionContract;

/**
 * Interface UserServiceContract
 * @package App\Services\Contracts
 */
interface UserServiceContract
{
    /**
     * Set action scenario.
     *
     * @param string $scenario
     * @return UserServiceContract
     */
    public function setScenario(string $scenario);
    
    /**
     * Create user model instance.
     *
     * @param array $attributes
     * @param bool $validate
     * @return User
     */
    public function create(array $attributes, bool $validate = true);
    
    /**
     * Save user model instance.
     *
     * @param User $user
     * @param array $attributes
     * @param bool $validate
     * @throws UserExceptionContract
     * @return User
     */
    public function save(User $user, array $attributes, bool $validate = true) : User;

    /**
     * Delete user model instance.
     *
     * @param User $user
     * @throws UserExceptionContract
     * @return int
     */
    public function delete(User $user) : int;

    /**
     * Get user model instance by ID.
     *
     * @param int $id
     * @throws UserExceptionContract
     * @return User
     */
    public function getById(int $id) : User;

    /**
     * Get user model instance by credentials.
     *
     * @param array $credentials
     * @return User
     */
    public function getByCredentials(array $credentials) : User;
}
