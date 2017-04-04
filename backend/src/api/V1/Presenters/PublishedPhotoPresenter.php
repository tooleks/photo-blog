<?php

namespace Api\V1\Presenters;

use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class PublishedPhotoPresenter.
 *
 * @property int id
 * @property int created_by_user_id
 * @property string absolute_url
 * @property string avg_color
 * @property string description
 * @property string created_at
 * @property string updated_at
 * @property ExifPresenter exif
 * @property Collection thumbnails
 * @property Collection tags
 * @package Api\V1\Presenters
 */
class PublishedPhotoPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap() : array
    {
        return [
            'id' => 'id',
            'created_by_user_id' => 'created_by_user_id',
            'url' => function () {
                $relativeUrl = $this->getPresenteeAttribute('relative_url');
                return $relativeUrl ? url(config('main.storage.url')) . $relativeUrl : null;
            },
            'avg_color' => 'avg_color',
            'description' => 'description',
            'created_at' => function () {
                $createdAt = $this->getPresenteeAttribute('created_at');
                return (string)$createdAt ?? null;
            },
            'updated_at' => function () {
                $updatedAt = $this->getPresenteeAttribute('updated_at');
                return (string)$updatedAt ?? null;
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
            'tags' => function () {
                $tags = collect($this->getPresenteeAttribute('tags'));
                return $tags->present(TagPresenter::class);
            },
        ];
    }
}
