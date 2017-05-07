<?php

namespace Core\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class TagBuilder.
 *
 * @package Core\Models\Builders
 */
class TagBuilder extends Builder
{
    /**
     * Delete all detached models.
     *
     * @return int
     */
    public function deleteAllDetached() : int
    {
        return $this
            ->getQuery()
            ->getConnection()
            ->table('tags')
            ->leftJoin('photo_tags', 'photo_tags.tag_id', '=', 'tags.id')
            ->whereNull('photo_tags.photo_id')
            ->delete();
    }
}
