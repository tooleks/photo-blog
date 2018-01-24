<?php

namespace App\Managers\Post\Contracts;

use App\Models\Post;

/**
 * Interface PostManager.
 *
 * @package App\Managers\Post\Contracts
 */
interface PostManager
{
    /**
     * Create a post.
     *
     * @param array $attributes
     * @return Post
     */
    public function create(array $attributes): Post;

    /**
     * Update a post.
     *
     * @param Post $post
     * @param array $attributes
     */
    public function update(Post $post, array $attributes = []): void;

    /**
     * Get a post by ID.
     *
     * @param int $id
     * @param array $filters
     * @return Post
     */
    public function getById(int $id, array $filters = []): Post;

    /**
     * Get a post before ID.
     *
     * @param int $id
     * @param array $filters
     * @return Post
     */
    public function getBeforeId(int $id, array $filters = []): Post;

    /**
     * Get a post after ID.
     *
     * @param int $id
     * @param array $filters
     * @return Post
     */
    public function getAfterId(int $id, array $filters = []): Post;

    /**
     * Paginate over posts.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = []);

    /**
     * Delete a post.
     *
     * @param Post $post
     * @return void
     */
    public function delete(Post $post): void;
}
