<?php

namespace Core\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TagManager.
 *
 * @package Core\Contracts
 */
interface TagManager
{
    /**
     * Paginate over tags.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator;
}
