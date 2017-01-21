<?php

namespace Api\V1\Models\Presenters;

use Tooleks\Laravel\Presenter\CollectionPresenter;

/**
 * Class ThumbnailCollectionPresenter
 *
 * @package Api\V1\Models\Presenters
 */
class ThumbnailCollectionPresenter extends CollectionPresenter
{
    /**
     * @inheritdoc
     */
    protected function getModelPresenterClass() : string
    {
        return ThumbnailPresenter::class;
    }
}
