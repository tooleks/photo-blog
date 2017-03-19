<?php

namespace Core\DataServices\Photo\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class HasTagText.
 *
 * @property string tagText
 * @package Core\DataServices\Photo\Criterias
 */
class HasTagText implements Criteria
{
    /**
     * HasTagText constructor.
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
