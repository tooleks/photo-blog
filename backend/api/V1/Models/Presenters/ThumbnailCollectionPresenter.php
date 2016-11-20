<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\CollectionPresenter;

/**
 * Class ThumbnailCollectionPresenter
 * @package Api\V1\Models\Presenters
 */
class ThumbnailCollectionPresenter extends CollectionPresenter
{
    /**
     * @inheritdoc
     */
    protected $entityPresenterClassName = ThumbnailPresenter::class;
}
