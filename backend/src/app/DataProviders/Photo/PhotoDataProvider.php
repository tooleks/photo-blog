<?php

namespace App\DataProviders\Photo;

use App\DataProviders\Photo\Contracts\PhotoDataProvider as PhotoDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class PhotoDataProvider.
 *
 * @package App\DataProviders
 */
class PhotoDataProvider extends DataProvider implements PhotoDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \App\Models\Photo::class;
    }
}
