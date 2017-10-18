<?php

namespace App\Managers\Tag;

use Closure;
use App\Models\Tag;
use App\Managers\Tag\Contracts\TagManager as TagManagerContract;
use Illuminate\Database\ConnectionInterface as DbConnection;
use Illuminate\Support\Collection;

/**
 * Class TagManager.
 *
 * @package App\Managers\Tag
 */
class TagManager implements TagManagerContract
{
    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * TagManager constructor.
     *
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @inheritdoc
     */
    public function paginateOverMostPopular(int $page, int $perPage, array $filters = [])
    {
        $query = (new Tag)
            ->newQuery()
            ->defaultSelect()
            ->orderByMostPopular();

        $paginator = $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);

        return $paginator;
    }

    /**
     * @inheritdoc
     */
    public function each(Closure $callback): void
    {
        (new Tag)
            ->newQuery()
            ->chunk(10, function (Collection $tags) use ($callback) {
                $tags->each($callback);
            });
    }
}
