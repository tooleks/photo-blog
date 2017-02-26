<?php

namespace Api\V1\Presenters;

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
 * @package Api\V1\Presenters
 */
class PhotoPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'absolute_url' => function () {
                return $this->getPresenteeAttribute('relative_url')
                    ? url(config('app.url')) . $this->getPresenteeAttribute('relative_url')
                    : null;
            },
            'avg_color' => 'avg_color',
            'created_at' => function () : string {
                return $this->getPresenteeAttribute('created_at') ?? null;
            },
            'updated_at' => function () : string {
                return $this->getPresenteeAttribute('updated_at') ?? null;
            },
            'exif' => function () {
                return new ExifPresenter($this->getPresenteeAttribute('exif'));
            },
            'thumbnails' => function () {
                $thumbnails = collect($this->getPresenteeAttribute('thumbnails'));
                return [
                    'medium' => new ThumbnailPresenter($thumbnails->get(0, [])),
                    'large' => new ThumbnailPresenter($thumbnails->get(1, [])),
                ];
            },
        ];
    }
}
