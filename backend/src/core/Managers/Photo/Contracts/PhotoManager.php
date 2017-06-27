<?php

namespace Core\Managers\Photo\Contracts;

use Closure;
use Core\Models\Photo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * Interface PhotoManager.
 *
 * @package Core\Managers\Photo\Contracts
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
    public function getNotPublishedById(int $id): Photo;

    /**
     * Get last fifty published photos.
     *
     * @return Collection
     */
    public function getLastFiftyPublished(): Collection;

    /**
     * Paginate over the last published photos.
     *
     * @param int $page
     * @param int $perPage
     * @param array $query
     * @return mixed
     */
    public function paginateOverLastPublished(int $page, int $perPage, array $query = []);

    /**
     * Apply the callback function on each photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function each(Closure $callback);

    /**
     * Apply the callback function on each published photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachPublished(Closure $callback);


    /**
     * Apply the callback function on each photo created by user ID.
     *
     * @param Closure $callback
     * @param int $createdByUserId
     * @return void
     */
    public function eachCreatedByUserId(Closure $callback, int $createdByUserId);

    /**
     * Apply the callback function on each not published photo older than week.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachNotPublishedOlderThanWeek(Closure $callback);

    /**
     * Determine if exists published photos older than week.
     *
     * @return bool
     */
    public function existsPublishedOlderThanWeek();

    /**
     * Create not published photo associated with the file.
     *
     * @param UploadedFile $file
     * @param int|null $createdByUserId
     * @param array $attributes
     * @param array $options
     * @return Photo
     */
    public function createNotPublishedWithFile(UploadedFile $file, int $createdByUserId = null, array $attributes = [], $options = []): Photo;

    /**
     * Save the photo filled with the attributes array.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function save(Photo $photo, array $attributes = [], array $options = []);

    /**
     * Publish the photo filled with the attributes array.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function publish(Photo $photo, array $attributes = [], array $options = []);

    /**
     * Save the photo associated with the file.
     *
     * @param Photo $photo
     * @param UploadedFile $file
     * @param array $attributes
     * @param array $options
     * @return void
     */
    public function saveWithFile(Photo $photo, UploadedFile $file, array $attributes = [], $options = []);

    /**
     * Delete the photo.
     *
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo);
}
