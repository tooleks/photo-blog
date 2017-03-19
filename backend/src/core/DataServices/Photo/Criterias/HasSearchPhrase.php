<?php

namespace Core\DataServices\Photo\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class HasSearchPhrase.
 *
 * @property string searchPhrase
 * @package Core\DataServices\Photo\Criterias
 */
class HasSearchPhrase implements Criteria
{
    /**
     * HasSearchPhrase constructor.
     *
     * @param string $searchPhrase
     */
    public function __construct($searchPhrase)
    {
        $this->searchPhrase = $searchPhrase;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->where(function ($query) {
            $query
                ->where('photos.description', 'like', "%{$this->searchPhrase}%")
                ->orWhereHas('tags', function ($query) {
                    $query->where('tags.text', 'like', "%{$this->searchPhrase}%");
                });
        });
    }
}
