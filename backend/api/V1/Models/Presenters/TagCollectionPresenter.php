<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\CollectionPresenter;

/**
 * Class TagCollectionPresenter
 * @package Api\V1\Models\Presenters
 */
class TagCollectionPresenter extends CollectionPresenter
{
    /**
     * @inheritdoc
     */
    protected $entityPresenterClassName = TagPresenter::class;
}
