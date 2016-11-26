<?php

namespace Api\V1\Models\Presenters;

use App\Core\Presenter\EntityPresenter;
use App\Models\DB\Photo;
use Exception;

/**
 * Class PhotoPresenter
 * @property Photo entity
 * @package Api\V1\Models\Presenters
 */
class PhotoPresenter extends EntityPresenter
{
    /**
     * PhotoPresenter constructor.
     *
     * @param Photo $entity
     * @throws Exception
     */
    public function __construct($entity)
    {
        parent::__construct($entity);

        if (!($entity instanceof Photo)) {
            throw new Exception('Invalid entity type.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function map()
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'absolute_url' => 'absolute_url',
            'description' => 'description',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'thumbnails' => 'thumbnails',
            'tags' => 'tags',
        ];
    }

    /**
     * @return string
     */
    public function createdAt()
    {
        return (string)$this->entity->created_at ?? null;
    }

    /**
     * @return string
     */
    public function updatedAt()
    {
        return (string)$this->entity->updated_at ?? null;
    }

    /**
     * @return string
     */
    public function absoluteUrl()
    {
        return $this->entity->relative_url ? url(config('main.website.url')) . $this->entity->relative_url : '';
    }

    /**
     * @return ThumbnailCollectionPresenter
     */
    public function thumbnails()
    {
        return new ThumbnailCollectionPresenter($this->entity->thumbnails);
    }

    /**
     * @return TagCollectionPresenter
     */
    public function tags()
    {
        return new TagCollectionPresenter($this->entity->tags);
    }
}
