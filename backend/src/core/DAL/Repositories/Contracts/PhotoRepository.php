<?php

namespace Core\DAL\Repositories\Contracts;

use Core\DAL\Models\Photo;
use Illuminate\Support\Collection;

/**
 * Interface PhotoRepository.
 *
 * @package Core\DAL\Repositories\Contracts
 */
interface PhotoRepository
{
    /**
     * Get a photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getPhotoById(int $id) : Photo;

    /**
     * Get a published photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getPublishedPhotoById(int $id) : Photo;

    /**
     * Get a uploaded photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getUploadedPhotoById(int $id) : Photo;

    /**
     * Find photos.
     *
     * @param int $take
     * @param int $skip
     * @param string|null $searchQuery
     * @param string|null $tag
     * @return Collection
     */
    public function findPublishedPhotos(int $take, int $skip, $searchQuery = null, $tag = null) : Collection;

    /**
     * Save a photo.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    public function savePublishedPhoto(Photo $photo, array $attributes = []);

    /**
     * Save an uploaded photo.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    public function saveUploadedPhoto(Photo $photo, array $attributes = []);

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return bool
     */
    public function deletePhoto(Photo $photo) : bool;
}
