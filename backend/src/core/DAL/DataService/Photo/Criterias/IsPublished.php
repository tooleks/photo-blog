<?php

namespace Core\DAL\DataService\Photo\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class IsPublished.
 *
 * @property bool isPublished
 * @package Core\DAL\DataService\Photo\Criterias
 */
class IsPublished implements Criteria
{
    /**
     * IsPublished constructor.
     *
     * @param bool $isPublished
     */
    public function __construct(bool $isPublished)
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->where('photos.is_published', $this->isPublished);
    }
}
