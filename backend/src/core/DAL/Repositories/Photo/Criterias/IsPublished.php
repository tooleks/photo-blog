<?php

namespace Core\DAL\Repositories\Photo\Criterias;

use Lib\Repositories\Contracts\Criteria;

/**
 * Class IsPublished.
 *
 * @property bool isPublished
 * @package Core\DAL\Repositories\Photo\Criterias
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
        return $query->where('photos.is_published', $this->isPublished);
    }
}
