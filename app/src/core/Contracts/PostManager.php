<?php

namespace Core\Contracts;

use Core\Entities\PostEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface PostManager.
 *
 * @package Core\Contracts
 */
interface PostManager
{
    /**
     * Create a post.
     *
     * @param array $attributes
     * @return PostEntity
     */
    public function create(array $attributes): PostEntity;

    /**
     * Update the post by ID.
     *
     * @param int $id
     * @param array $attributes
     * @return PostEntity
     */
    public function updateById(int $id, array $attributes): PostEntity;

    /**
     * Get the post by ID.
     *
     * @param int $id
     * @param array $filters
     * @return PostEntity
     */
    public function getById(int $id, array $filters = []): PostEntity;

    /**
     * Get the post before ID.
     *
     * @param int $id
     * @param array $filters
     * @return PostEntity
     */
    public function getBeforeId(int $id, array $filters = []): PostEntity;

    /**
     * Get the post after ID.
     *
     * @param int $id
     * @param array $filters
     * @return PostEntity
     */
    public function getAfterId(int $id, array $filters = []): PostEntity;

    /**
     * Paginate over posts.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator;

    /**
     * Delete the post by ID.
     *
     * @param int $id
     * @return PostEntity
     */
    public function deleteById(int $id): PostEntity;
}
