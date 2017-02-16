<?php

namespace Core\DAL\DataService\Photo;

use Lib\DataService\DataService;

/**
 * Class PhotoDataService.
 *
 * @package Core\DAL\DataService
 */
class PhotoDataService extends DataService
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\DAL\Models\Photo::class;
    }
}
