<?php

namespace Core\DAL\Repositories\Photo\Criterias;

use Lib\Repositories\Contracts\Criteria;

/**
 * Class WhereSearchQuery.
 *
 * @property string searchQuery
 * @package Core\DAL\Repositories\Photo\Criterias
 */
class WhereSearchQuery implements Criteria
{
    /**
     * WhereSearchQuery constructor.
     *
     * @param string $searchQuery
     */
    public function __construct($searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        return $query->select('photos.*')
            ->join('photo_tags AS wsqc_photo_tags', 'wsqc_photo_tags.photo_id', '=', 'photos.id')
            ->join('tags AS wsqc_tags', 'wsqc_tags.id', '=', 'wsqc_photo_tags.tag_id')
            ->where(function ($query) {
                $query->where('photos.description', 'like', "%{$this->searchQuery}%")
                    ->orWhere('wsqc_tags.text', 'like', "%{$this->searchQuery}%");
            })
            ->groupBy('photos.id');
    }
}
