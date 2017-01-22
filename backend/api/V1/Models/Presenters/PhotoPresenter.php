<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Photo;
use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class PhotoPresenter.
 *
 * @property Photo originalModel
 * @package Api\V1\Models\Presenters
 */
class PhotoPresenter extends ModelPresenter
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
            // 'model_presenter_attribute_name' => 'original_model_attribute_name'
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
     * @return Collection
     */
    public function getThumbnailsAttribute()
    {
        return $this->originalModel->thumbnails->map(function ($item) {
            return new ThumbnailPresenter($item);
        });
    }

    /**
     * @return Collection
     */
    public function getTagsAttribute()
    {
        return $this->originalModel->tags->map(function ($item) {
            return new TagPresenter($item);
        });
    }
}
