<?php

namespace Core\DataProviders\Photo\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class IsPublished.
 *
 * @property bool value
 * @package Core\DataProviders\Photo\Criterias
 */
class IsPublished implements Criteria
{
    /**
     * IsPublished constructor.
     *
     * @param bool $value
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->where('photos.is_published', $this->value);
    }
}
