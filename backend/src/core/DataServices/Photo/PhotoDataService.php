<?php

namespace Core\DataServices\Photo;

use Core\DataServices\Photo\Contracts\PhotoDataService as PhotoDataServiceContract;
use Lib\DataService\DataService;

/**
 * Class PhotoDataService.
 *
 * @package Core\DataServices
 */
class PhotoDataService extends DataService implements PhotoDataServiceContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\Models\Photo::class;
    }
}
