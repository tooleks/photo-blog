<?php

namespace Core\Managers\Photo\Contracts;

use Closure;
use Core\Models\Photo;
use Illuminate\Http\UploadedFile;

/**
 * Interface PhotoManager.
 *
 * @package Core\Managers\Photo\Contracts
 */
interface PhotoManager
{
    /**
     * Get the photo by its ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getById(int $id);

    /**
     * Get the published photo by its ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getPublishedById(int $id);

    /**
     * Get the not published photo by its ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getNotPublishedById(int $id);

    /**
     * Paginate over the published photos.
     *
     * @param int $page
     * @param int $perPage
     * @param array $attributes
     * @return mixed
     */
    public function paginateOverPublished(int $page, int $perPage, array $attributes = []);

    /**
     * Apply callback function on each not published photo older than week.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachNotPublishedOlderThanWeek(Closure $callback);

    /**
     * Save the photo filled with the attributes array.
     *
     * @param Photo $photo
     * @param array $attributes
     * @return void
     */
    public function save(Photo $photo, array $attributes = []);

    /**
     * Save the photo associated with the uploaded file.
     *
     * @param Photo $photo
     * @param UploadedFile $file
     * @return void
     */
    public function saveWithUploadedFile(Photo $photo, UploadedFile $file);

    /**
     * Delete the photo with its files.
     *
     * @param Photo $photo
     * @return void
     */
    public function deleteWithFiles(Photo $photo);
}
