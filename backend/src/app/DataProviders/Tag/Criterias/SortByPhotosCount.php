<?php

namespace App\DataProviders\Tag\Criterias;

use Lib\DataProvider\Contracts\Criteria;
use Lib\DataProvider\Eloquent\Criterias\Templates\SortBy;

/**
 * Class SortByPhotosCount.
 *
 * @package App\DataProviders\Tag\Criterias
 */
class SortByPhotosCount extends SortBy implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query): void
    {
        $query
            ->selectRaw('tags.*, COUNT(photo_tags.tag_id) AS count')
            ->leftJoin('photo_tags', 'photo_tags.tag_id', '=', 'tags.id')
            ->groupBy('tags.id')
            ->orderBy('count', $this->order);
    }
}
