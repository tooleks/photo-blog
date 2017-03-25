<?php

namespace Core\DataProviders\Tag;

use Core\DataProviders\Tag\Contracts\TagDataProvider as TagDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class TagDataProvider.
 *
 * @package Core\DataProviders
 */
class TagDataProvider extends DataProvider implements TagDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass() : string
    {
        return \Core\Models\Tag::class;
    }
}
