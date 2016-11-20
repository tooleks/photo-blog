<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\CollectionPresenter;

/**
 * Class PhotoCollectionPresenter
 * @package Api\V1\Models\Presenters
 */
class PhotoCollectionPresenter extends CollectionPresenter
{
    /**
     * @inheritdoc
     */
    protected $entityPresenterClassName = PhotoPresenter::class;
}
