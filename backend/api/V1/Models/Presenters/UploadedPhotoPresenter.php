<?php

namespace Api\V1\Models\Presenters;

use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class UploadedPhotoPresenter.
 *
 * @property int id
 * @property int user_id
 * @property string absolute_url
 * @property string created_at
 * @property string updated_at
 * @property Collection thumbnails
 * @package Api\V1\Models\Presenters
 */
class UploadedPhotoPresenter extends Presenter
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
            'absolute_url' => function () {
                return $this->getPresenteeAttribute('relative_url') ? url(config('app.url')) . $this->getPresenteeAttribute('relative_url') : '';
            },
            'created_at' => function () {
                return (string)$this->getPresenteeAttribute('created_at') ?? null;
            },
            'updated_at' => function () {
                return (string)$this->getPresenteeAttribute('updated_at') ?? null;
            },
            'exif' => function () {
                return new ExifPresenter($this->getPresenteeAttribute('exif'));
            },
            'thumbnails' => function () {
                return collect($this->getPresenteeAttribute('thumbnails'))->present(ThumbnailPresenter::class);
            },
        ];
    }
}
