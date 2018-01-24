<?php

namespace App\Managers\Photo\Contracts;

use App\Models\Photo;

/**
 * Interface PhotoManager.
 *
 * @package App\Managers\Photo\Contracts
 */
interface PhotoManager
{
    /**
     * Create a photo.
     *
     * @param array $attributes
     * @return Photo
     */
    public function create(array $attributes = []): Photo;

    /**
     * Get a photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getById(int $id): Photo;

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo): void;
}
