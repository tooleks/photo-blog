<?php

namespace Core\DataProviders\Photo;

use Core\DataProviders\Photo\Contracts\PhotoDataProvider as PhotoDataProviderContract;
use Lib\DataProvider\DataProvider;

/**
 * Class PhotoDataProvider.
 *
 * @package Core\DataProviders
 */
class PhotoDataProvider extends DataProvider implements PhotoDataProviderContract
{
    /**
     * @inheritdoc
     */
    public function getModelClass(): string
    {
        return \Core\Models\Photo::class;
    }
}
