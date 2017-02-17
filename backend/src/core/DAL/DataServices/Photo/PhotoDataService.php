<?php

namespace Core\DAL\DataServices\Photo;

use Core\DAL\DataServices\Photo\Contracts\PhotoDataService as PhotoDataServiceContract;
use Lib\DataService\DataService;

/**
 * Class PhotoDataService.
 *
 * @package Core\DAL\DataServices
 */
class PhotoDataService extends DataService implements PhotoDataServiceContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\DAL\Models\Photo::class;
    }
}
