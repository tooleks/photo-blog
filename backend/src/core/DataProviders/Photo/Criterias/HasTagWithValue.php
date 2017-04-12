<?php

namespace Core\DataProviders\Photo\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class HasTagWithValue.
 *
 * @property string tagValue
 * @package Core\DataProviders\Photo\Criterias
 */
class HasTagWithValue implements Criteria
{
    /**
     * HasTagWithValue constructor.
     *
     * @param string $tagValue
     */
    public function __construct($tagValue)
    {
        $this->tagValue = $tagValue;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->whereHas('tags', function ($query) {
            $query->where('tags.value', $this->tagValue);
        });
    }
}
