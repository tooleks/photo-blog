<?php

namespace App\Managers\Tag\Contracts;

use Closure;

/**
 * Interface TagManager.
 *
 * @package App\Managers\Tag\Contracts
 */
interface TagManager
{
    /**
     * Paginate over the most popular tags.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginateOverMostPopular(int $page, int $perPage, array $filters = []);

    /**
     * Apply the callback function on each tag.
     *
     * @param Closure $callback
     * @return void
     */
    public function each(Closure $callback): void;
}
