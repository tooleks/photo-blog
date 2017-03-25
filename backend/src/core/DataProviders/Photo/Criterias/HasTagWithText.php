<?php

namespace Core\DataProviders\Photo\Criterias;

use Lib\DataProvider\Contracts\Criteria;

/**
 * Class HasTagWithText.
 *
 * @property string tagText
 * @package Core\DataProviders\Photo\Criterias
 */
class HasTagWithText implements Criteria
{
    /**
     * HasTagWithText constructor.
     *
     * @param string $tagText
     */
    public function __construct($tagText)
    {
        $this->tagText = $tagText;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->whereHas('tags', function ($query) {
            $query->where('tags.text', $this->tagText);
        });
    }
}
