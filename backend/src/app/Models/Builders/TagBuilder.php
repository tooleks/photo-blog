<?php

namespace App\Models\Builders;

use App\Models\Tables\Constant;
use Illuminate\Database\Eloquent\Builder;
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
    private $tagsTable = Constant::TABLE_TAGS;

    /**
     * @var string
     */
    private $postsTagsTable = Constant::TABLE_POSTS_TAGS;

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
    public function whereHasPosts()
    {
        return $this->has('posts');
    }

    /**
     * @return $this
     */
    public function whereHasNoPosts()
    {
        return $this->doesntHave('posts');
    }

    /**
     * @return $this
     */
    public function orderByPopularity()
    {
        return $this
            ->addSelect(new Expression("COUNT({$this->postsTagsTable}.tag_id) AS popularity"))
            ->leftJoin($this->postsTagsTable, "{$this->postsTagsTable}.tag_id", '=', "{$this->tagsTable}.id")
            ->groupBy("{$this->tagsTable}.id")
            ->orderBy('popularity', 'desc');
    }
}
