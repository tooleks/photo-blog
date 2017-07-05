<?php

namespace Core\Managers\Tag;

use Closure;
use Core\DataProviders\Tag\Contracts\TagDataProvider;
use Core\DataProviders\Tag\Criterias\SortByPhotosCount;
use Core\Managers\Tag\Contracts\TagManager as TagManagerContract;
use Illuminate\Pagination\AbstractPaginator;

/**
 * Class TagManager.
 *
 * @package Core\Managers\Tag
 */
class TagManager implements TagManagerContract
{
    /**
     * @var TagDataProvider
     */
    private $tagDataProvider;

    /**
     * TagManager constructor.
     *
     * @param TagDataProvider $tagDataProvider
     */
    public function __construct(TagDataProvider $tagDataProvider)
    {
        $this->tagDataProvider = $tagDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function paginateOverMostPopular(int $page, int $perPage, array $query = []): AbstractPaginator
    {
        return $this->tagDataProvider
            ->applyCriteria((new SortByPhotosCount)->desc())
            ->paginate($page, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function each(Closure $callback): void
    {
        $this->tagDataProvider->each($callback);
    }
}
