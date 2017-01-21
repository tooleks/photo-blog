<?php

namespace Api\V1\Models\Presenters;

use Tooleks\Laravel\Presenter\CollectionPresenter;

/**
 * Class PhotoCollectionPresenter.
 *
 * @package Api\V1\Models\Presenters
 */
class PhotoCollectionPresenter extends CollectionPresenter
{
    /**
     * @inheritdoc
     */
    protected function getModelPresenterClass() : string
    {
        return PhotoPresenter::class;
    }
}
