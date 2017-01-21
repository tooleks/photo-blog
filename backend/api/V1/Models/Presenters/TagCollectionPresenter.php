<?php

namespace Api\V1\Models\Presenters;

use Tooleks\Laravel\Presenter\CollectionPresenter;

/**
 * Class TagCollectionPresenter
 *
 * @package Api\V1\Models\Presenters
 */
class TagCollectionPresenter extends CollectionPresenter
{
    /**
     * @inheritdoc
     */
    protected function getModelPresenterClass() : string
    {
        return TagPresenter::class;
    }
}
