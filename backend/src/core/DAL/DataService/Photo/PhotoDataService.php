<?php

namespace Core\DAL\DataService\Photo;

use Core\DAL\DataService\Photo\Contracts\PhotoDataService as PhotoDataServiceContract;
use Lib\DataService\DataService;

/**
 * Class PhotoDataService.
 *
 * @package Core\DAL\DataService
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
