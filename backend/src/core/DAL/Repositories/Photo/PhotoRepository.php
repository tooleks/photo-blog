<?php

namespace Core\DAL\Repositories\Photo;

use Lib\Repositories\Repository;

/**
 * Class PhotoRepository.
 *
 * @package Core\DAL\Repositories
 */
class PhotoRepository extends Repository
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\DAL\Models\Photo::class;
    }
}
