<?php

namespace App\Models\Builders;

use App\Models\Photo;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Class PhotoBuilder.
 *
 * @package App\Models\Builders
 */
class PhotoBuilder extends Builder
{
    /**
     * @var string
     */
    private $photosTable;

    /**
     * @var string
     */
    private $tagsTable;

    /**
     * PhotoBuilder constructor.
     *
     * @param QueryBuilder $query
     */
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);

        $this->photosTable = (new Photo)->getTable();
        $this->tagsTable = (new Tag)->getTable();
    }

    /**
     * @return $this
     */
    public function defaultSelect()
    {
        return $this->select("{$this->photosTable}.*");
    }

    /**
     * @return $this
     */
    public function withExif()
    {
        return $this->with('exif');
    }

    /**
     * @return $this
     */
    public function withThumbnails()
    {
        return $this->with('thumbnails');
    }

    /**
     * @return $this
     */
    public function withTags()
    {
        return $this->with('tags');
    }

    /**
     * @param int $id
     * @return $this
     */
    public function whereIdEquals(int $id)
    {
        return $this->where("{$this->photosTable}.id", $id);
    }

    /**
     * @param int $createdByUserId
     * @return $this
     */
    public function whereCreatedByUserIdEquals(int $createdByUserId)
    {
        return $this->where("{$this->photosTable}.created_by_user_id", $createdByUserId);
    }

    /**
     * @return $this
     */
    public function wherePublished()
    {
        return $this->whereNotNull("{$this->photosTable}.published_at");
    }

    /**
     * @return $this
     */
    public function whereUnpublished()
    {
        return $this->whereNull("{$this->photosTable}.published_at");
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function whereCreatedAtGreaterThan(Carbon $date)
    {
        return $this->where("{$this->photosTable}.created_at", '>', $date);
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function whereUpdatedAtLessThan(Carbon $date)
    {
        return $this->where("{$this->photosTable}.updated_at", '<', $date);
    }

    /**
     * @param string $tagValue
     * @return Builder|static
     */
    public function whereTagValueEquals(string $tagValue)
    {
        return $this->whereHas('tags', function (Builder $query) use ($tagValue) {
            $query->where("{$this->tagsTable}.value", $tagValue);
        });
    }

    /**
     * @param string $searchPhrase
     * @return $this
     */
    public function searchPhrase(string $searchPhrase)
    {
        $whereSearchPhraseScope = function (Builder $query, string $searchPhrase) {
            $query
                ->where("{$this->photosTable}.description", 'like', "%{$searchPhrase}%")
                ->orWhereHas('tags', function (Builder $query) use ($searchPhrase) {
                    $query->where("{$this->tagsTable}.value", 'like', "%{$searchPhrase}%");
                });
        };

        return $this->where(function (Builder $query) use ($whereSearchPhraseScope, $searchPhrase) {
            // Search by whole search phrase.
            $query->where(function (Builder $query) use ($whereSearchPhraseScope, $searchPhrase) {
                $whereSearchPhraseScope($query, $searchPhrase);
            });

            $searchPhraseWords = explode(' ', $searchPhrase);
            if (count($searchPhraseWords) > 1) {
                $query->orWhere(function (Builder $query) use ($whereSearchPhraseScope, $searchPhraseWords) {
                    // Search by each search phrase word separately.
                    foreach ($searchPhraseWords as $searchPhrase) {
                        $query->where(function (Builder $query) use ($whereSearchPhraseScope, $searchPhrase) {
                            $whereSearchPhraseScope($query, $searchPhrase);
                        });
                    }
                });
            }
        });
    }

    /**
     * @return $this
     */
    public function orderByCreatedAtDesc()
    {
        return $this->orderBy("{$this->photosTable}.created_at", 'desc');
    }
}
