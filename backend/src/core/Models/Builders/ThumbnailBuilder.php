<?php

namespace Core\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ThumbnailBuilder.
 *
 * @package Core\Models\Builders
 */
class ThumbnailBuilder extends Builder
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
            ->table('thumbnails')
            ->leftJoin('photo_thumbnails', 'photo_thumbnails.thumbnail_id', '=', 'thumbnails.id')
            ->whereNull('photo_thumbnails.photo_id')
            ->delete();
    }
}
