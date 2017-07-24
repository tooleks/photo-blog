<?php

namespace App\DataProviders\Photo;

use App\DataProviders\Photo\Contracts\PhotoDataProvider as PhotoDataProviderContract;
use Lib\DataProvider\Eloquent\EloquentDataProvider;

/**
 * Class PhotoDataProvider.
 *
 * @package App\DataProviders
 */
class PhotoDataProvider extends EloquentDataProvider implements PhotoDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\Photo::class;
    }
}
