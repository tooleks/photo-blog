<?php

namespace Core\DataServices\Tag\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class OrderByPhotosCountDesc.
 *
 * @package Core\DataServices\Tag\Criterias
 */
class OrderByPhotosCountDesc implements Criteria
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
            ->orderBy('count', 'desc');
    }
}
