<?php

namespace App\DataProviders\Photo\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class IsPublished.
 *
 * @package App\DataProviders\Photo\Criterias
 */
class IsPublished implements Criteria
{
    /**
     * @var bool
     */
    private $value;

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
    public function apply($query): void
    {
        $query->where('photos.is_published', $this->value);
    }
}
