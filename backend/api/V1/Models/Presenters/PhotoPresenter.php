<?php

namespace Api\V1\Models\Presenters;

use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class PhotoPresenter.
 *
 * @property int id
 * @property int user_id
 * @property string absolute_url
 * @property string created_at
 * @property string updated_at
 * @property Collection thumbnails
 * @property Collection tags
 * @package Api\V1\Models\Presenters
 */
class PhotoPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            // 'presenter_attribute_name' => 'presentee_attribute_name'
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
        return $this->getPresenteeAttribute('relative_url') ? url(config('app.url')) . $this->getPresenteeAttribute('relative_url') : '';
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return (string)$this->getPresenteeAttribute('created_at') ?? null;
    }

    /**
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        return (string)$this->getPresenteeAttribute('updated_at') ?? null;
    }

    /**
     * @return ExifPresenter
     */
    public function getExifAttribute()
    {
        return new ExifPresenter($this->getPresenteeAttribute('exif'));
    }

    /**
     * @return Collection
     */
    public function getTagsAttribute()
    {
        return collect($this->getPresenteeAttribute('tags'))->present(TagPresenter::class);
    }

    /**
     * @return Collection
     */
    public function getThumbnailsAttribute()
    {
        return collect($this->getPresenteeAttribute('thumbnails'))->present(ThumbnailPresenter::class);
    }
}
