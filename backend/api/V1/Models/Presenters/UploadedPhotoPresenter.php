<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Photo;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class UploadedPhotoPresenter
 *
 * @property Photo originalModel
 * @package Api\V1\Models\Presenters
 */
class UploadedPhotoPresenter extends ModelPresenter
{
    /**
     * @inheritdoc
     */
    protected function getOriginalModelClass() : string
    {
        return Photo::class;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'absolute_url' => 'absolute_url',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'thumbnails' => 'thumbnails',
        ];
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return (string)$this->originalModel->created_at ?? null;
    }

    /**
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        return (string)$this->originalModel->updated_at ?? null;
    }

    /**
     * @return string
     */
    public function getAbsoluteUrlAttribute()
    {
        return $this->originalModel->relative_url ? url(config('app.url')) . $this->originalModel->relative_url : '';
    }

    /**
     * @return ThumbnailCollectionPresenter
     */
    public function getThumbnailsAttribute()
    {
        return new ThumbnailCollectionPresenter($this->originalModel->thumbnails);
    }
}
