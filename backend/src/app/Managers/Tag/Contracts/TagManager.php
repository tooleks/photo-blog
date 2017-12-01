<?php

namespace App\Managers\Tag\Contracts;

/**
 * Interface TagManager.
 *
 * @package App\Managers\Tag\Contracts
 */
interface TagManager
{
    /**
     * Paginate over tags.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = []);
}
