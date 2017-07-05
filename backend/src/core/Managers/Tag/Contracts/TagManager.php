<?php

namespace Core\Managers\Tag\Contracts;

use Closure;
use Illuminate\Pagination\AbstractPaginator;

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
    public function paginateOverMostPopular(int $page, int $perPage, array $query = []): AbstractPaginator;

    /**
     * Apply the callback function on each tag.
     *
     * @param Closure $callback
     * @return void
     */
    public function each(Closure $callback): void;
}
