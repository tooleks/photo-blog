<?php

namespace Core\DAL\Repositories\Photo\Criterias;

use Lib\Repositories\Contracts\Criteria;

/**
 * Class IsPublished.
 *
 * @package Core\DAL\Repositories\Photo\Criterias
 */
class IsPublished implements Criteria
{
    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        return $query->where('photos.is_published', true);
    }
}
