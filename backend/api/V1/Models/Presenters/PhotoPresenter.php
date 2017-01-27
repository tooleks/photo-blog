<?php

namespace Api\V1\Models\Presenters;

use App\Models\DB\Photo;
use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\ModelPresenter;

/**
 * Class PhotoPresenter.
 *
 * @property Photo originalModel
 * @property int id
 * @property int user_id
 * @property string absolute_url
 * @property string created_at
 * @property string updated_at
 * @property Collection thumbnails
 * @property Collection tags
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
            'absolute_url' => null,
            'description' => 'description',
            'created_at' => null,
            'updated_at' => null,
            'exif' => null,
            'tags' => null,
            'thumbnails' => null,
        ];
    }

    /**
     * @return string
     */
    public function getAbsoluteUrlAttribute()
    {
        return $this->originalModel->relative_url ? url(config('app.url')) . $this->originalModel->relative_url : '';
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
     * @return ExifPresenter
     */
    public function getExifAttribute()
    {
        return new ExifPresenter($this->originalModel->exif);
    }

    /**
     * @return Collection
     */
    public function getTagsAttribute()
    {
        return collect($this->originalModel->tags)->map(function ($item) {
            return new TagPresenter($item);
        });
    }

    /**
     * @return Collection
     */
    public function getThumbnailsAttribute()
    {
        return collect($this->originalModel->thumbnails)->map(function ($item) {
            return new ThumbnailPresenter($item);
        });
    }
}
