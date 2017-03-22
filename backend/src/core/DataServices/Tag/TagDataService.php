<?php

namespace Core\DataServices\Tag;

use Core\DataServices\Tag\Contracts\TagDataService as TagDataServiceContract;
use Lib\DataService\DataService;

/**
 * Class TagDataService.
 *
 * @package Core\DataServices
 */
class TagDataService extends DataService implements TagDataServiceContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\Models\Tag::class;
    }
}
