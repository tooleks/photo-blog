<?php

namespace Core\DataProviders\Photo\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class HasTagWithValue.
 *
 * @property string value
 * @package Core\DataProviders\Photo\Criterias
 */
class HasTagWithValue implements Criteria
{
    /**
     * HasTagWithValue constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->whereHas('tags', function ($query) {
            $query->where('tags.value', $this->value);
        });
    }
}
