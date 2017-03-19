<?php

namespace Core\DataServices\Photo\Criterias;

use Lib\DataService\Contracts\Criteria;

/**
 * Class Search.
 *
 * @property string query
 * @package Core\DataServices\Photo\Criterias
 */
class Search implements Criteria
{
    /**
     * Search constructor.
     *
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @inheritdoc
     */
    public function apply($query)
    {
        $query->where(function ($query) {
            $query
                ->where('photos.description', 'like', "%{$this->query}%")
                ->orWhereHas('tags', function ($query) {
                    $query->where('tags.text', 'like', "%{$this->query}%");
                });
        });
    }
}
