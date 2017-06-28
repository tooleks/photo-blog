<?php

namespace Core\Managers\Tag\Contracts;

use Closure;

/**
 * Interface TagManager.
 *
 * @package Core\Managers\Tag\Contracts
 */
interface TagManager
{
    /**
     * Paginate over the most popular tags.
     *
     * @param int $page
     * @param int $perPage
     * @param array $query
     * @return mixed
     */
    public function paginateOverMostPopular(int $page, int $perPage, array $query = []);

    /**
     * Apply the callback function on each tag.
     *
     * @param Closure $callback
     * @return void
     */
    public function each(Closure $callback): void;
}
