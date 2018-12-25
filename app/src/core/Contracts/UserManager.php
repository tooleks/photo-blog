<?php

namespace Core\Contracts;

use Core\Entities\UserEntity;

/**
 * Interface UserManager.
 *
 * @package Core\Contracts
 */
interface UserManager
{
    /**
     * Create a user.
     *
     * @param array $attributes
     * @return UserEntity
     */
    public function create(array $attributes): UserEntity;

    /**
     * Update the user by ID.
     *
     * @param int $id
     * @param array $attributes
     * @return UserEntity
     */
    public function updateById(int $id, array $attributes): UserEntity;

    /**
     * Get the user by ID.
     *
     * @param int $id
     * @return UserEntity
     */
    public function getById(int $id): UserEntity;

    /**
     * Get the user by name.
     *
     * @param string $name
     * @return UserEntity
     */
    public function getByName(string $name): UserEntity;

    /**
     * Delete the user by ID.
     *
     * @param int $id
     * @return UserEntity
     */
    public function deleteById(int $id): UserEntity;
}
