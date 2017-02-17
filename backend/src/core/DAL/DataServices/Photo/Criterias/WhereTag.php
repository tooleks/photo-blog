<?php

namespace Core\DAL\DataServices\Photo\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class WhereTag.
 *
 * @property string tag
 * @package Core\DAL\DataServices\Photo\Criterias
 */
class WhereTag implements Criteria
{
    /**
     * WhereTag constructor.
     *
     * @param string $tag
     */
    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->select('photos.*')
            ->join('photo_tags AS wtc_photo_tags', 'wtc_photo_tags.photo_id', '=', 'photos.id')
            ->join('tags AS wtc_tags', 'wtc_tags.id', '=', 'wtc_photo_tags.tag_id')
            ->where('wtc_tags.text', 'like', "%{$this->tag}%")
            ->groupBy('photos.id');
    }
}
