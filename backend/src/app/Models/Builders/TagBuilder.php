<?php

namespace App\Models\Builders;

use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;

/**
 * Class TagBuilder.
 *
 * @package App\Models\Builders
 */
class TagBuilder extends Builder
{
    /**
     * @var string
     */
    private $tagsTable;

    /**
     * @var string
     */
    private $photosTable;

    /**
     * @var string
     */
    private $photosTagsTable;

    /**
     * TagBuilder constructor.
     *
     * @param QueryBuilder $query
     */
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);

        $this->tagsTable = (new Tag)->getTable();
        $this->photosTable = (new Photo)->getTable();
        $this->photosTagsTable = $this->photosTable . '_' . $this->tagsTable;
    }

    /**
     * @return $this
     */
    public function defaultSelect()
    {
        return $this->select("{$this->tagsTable}.*");
    }

    /**
     * @return $this
     */
    public function whereHasNoPhotos()
    {
        return $this->has('photos', '<', 1);
    }

    /**
     * @param string $order
     * @return $this
     */
    public function orderByMostPopular(string $order = 'desc')
    {
        return $this
            ->addSelect(new Expression("COUNT({$this->photosTagsTable}.tag_id) AS count"))
            ->leftJoin($this->photosTagsTable, "{$this->photosTagsTable}.tag_id", '=', "{$this->tagsTable}.id")
            ->groupBy("{$this->tagsTable}.id")
            ->orderBy('count', $order);
    }
}
