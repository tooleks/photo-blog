<?php

namespace Core\DataServices\Tag\Criterias;

use Lib\DataService\Contracts\Criteria;
use Lib\DataService\Criterias\Templates\SortBy;

/**
 * Class SortByPhotosCount.
 *
 * @package Core\DataServices\Tag\Criterias
 */
class SortByPhotosCount extends SortBy implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query
            ->selectRaw('tags.*, COUNT(photo_tags.tag_id) AS count')
            ->leftJoin('photo_tags', 'photo_tags.tag_id', '=', 'tags.id')
            ->groupBy('tags.id')
            ->orderBy('count', $this->order);
    }
}
