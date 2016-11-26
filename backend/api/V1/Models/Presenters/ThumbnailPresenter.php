<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\EntityPresenter;
use App\Models\DB\Thumbnail;
use Exception;

/**
 * Class TokenPresenter
 * @property Thumbnail $entity
 * @package Api\V1\Models\Presenters
 */
class ThumbnailPresenter extends EntityPresenter
{
    /**
     * ThumbnailPresenter constructor.
     *
     * @param Thumbnail $entity
     * @throws Exception
     */
    public function __construct($entity)
    {
        parent::__construct($entity);

        if (!($entity instanceof Thumbnail)) {
            throw new Exception('Invalid entity type.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function map()
    {
        return [
            'absolute_url' => 'absolute_url',
            'width' => 'width',
            'height' => 'height',
        ];
    }

    /**
     * @return string
     */
    public function absoluteUrl()
    {
        return $this->entity->relative_url ? url(config('main.website.url')) . $this->entity->relative_url : '';
    }
}
