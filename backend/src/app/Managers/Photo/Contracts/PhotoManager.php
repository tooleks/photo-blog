<?php

namespace App\Managers\Photo\Contracts;

use Closure;
use App\Models\Photo;
use Illuminate\Support\Collection;

/**
 * Interface PhotoManager.
 *
 * @package App\Managers\Photo\Contracts
 */
interface PhotoManager
{
    /**
     * Get the photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getById(int $id): Photo;

    /**
     * Get published photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getPublishedById(int $id): Photo;

    /**
     * Get not published photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getUnpublishedById(int $id): Photo;

    /**
     * Get newly published photos.
     *
     * @param int $take
     * @param int $skip
     * @return Collection
     */
    public function getNewlyPublished(int $take = 10, int $skip = 0): Collection;

    /**
     * Paginate over newly published photos.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginateOverNewlyPublished(int $page, int $perPage, array $filters = []);

    /**
     * Apply the callback function on each photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function each(Closure $callback): void;

    /**
     * Apply the callback function on each published photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachPublished(Closure $callback): void;

    /**
     * Apply the callback function on each photo unpublished greater than week ago.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachUnpublishedGreaterThanWeekAgo(Closure $callback): void;

    /**
     * Determine if exists photos published less than week ago.
     *
     * @return bool
     */
    public function existsPublishedLessThanWeekAgo(): bool;

    /**
     * Create the photo by uploaded file.
     *
     * @param array $attributes
     * @return Photo
     */
    public function createByFile(array $attributes = []): Photo;

    /**
     * Save the photo by uploaded file.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    public function saveByFile(Photo $photo, array $attributes = []): void;

    /**
     * Generate the photo thumbnails.
     *
     * @param Photo $photo
     * @return void
     */
    public function generateThumbnails(Photo $photo): void;

    /**
     * Save the photo by attributes.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    public function saveByAttributes(Photo $photo, array $attributes = []): void;

    /**
     * Delete the photo.
     *
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo): void;
}
