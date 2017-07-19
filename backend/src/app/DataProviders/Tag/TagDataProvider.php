<?php

namespace App\DataProviders\Tag;

use App\DataProviders\Tag\Contracts\TagDataProvider as TagDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class TagDataProvider.
 *
 * @package App\DataProviders
 */
class TagDataProvider extends DataProvider implements TagDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\Tag::class;
    }
}
