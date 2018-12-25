<?php

namespace Core\Contracts;

use Core\Entities\PhotoEntity;

/**
 * Interface PhotoManager.
 *
 * @package Core\Contracts
 */
interface PhotoManager
{
    /**
     * Create a photo.
     *
     * @param array $attributes
     * @return PhotoEntity
     */
    public function create(array $attributes): PhotoEntity;

    /**
     * Update the photo by ID.
     *
     * @param int $id
     * @param array $attributes
     * @return PhotoEntity
     */
    public function updateById($id, array $attributes): PhotoEntity;

    /**
     * Get the photo by ID.
     *
     * @param int $id
     * @return PhotoEntity
     */
    public function getById(int $id): PhotoEntity;

    /**
     * Delete the photo by ID.
     *
     * @param int $id
     * @return PhotoEntity
     */
    public function deleteById(int $id): PhotoEntity;
}
