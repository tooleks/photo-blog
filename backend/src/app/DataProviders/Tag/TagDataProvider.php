<?php

namespace App\DataProviders\Tag;

use App\DataProviders\Tag\Contracts\TagDataProvider as TagDataProviderContract;
use Lib\DataProvider\Eloquent\EloquentDataProvider;

/**
 * Class TagDataProvider.
 *
 * @package App\DataProviders
 */
class TagDataProvider extends EloquentDataProvider implements TagDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\Tag::class;
    }
}
