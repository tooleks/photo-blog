<?php

namespace Core\Rss\Presenters;

use Illuminate\Support\Facades\Storage;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class PhotoPresenter.
 *
 * @property string url
 * @property string title
 * @property string description
 * @property string file_url
 * @property string file_size
 * @property array categories
 * @package Core\Rss\Presenters
 */
class PhotoPresenter extends Presenter
{
    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'url' => function () {
                return sprintf(config('format.frontend.url.photo_page'), $this->getPresenteeAttribute('id'));
            },
            'title' => 'description',
            'description' => function () {
                $exif = new ExifPresenter($this->getPresenteeAttribute('exif'));
                if ($exif->manufacturer) {
                    $values[] = sprintf('%s: %s', trans('attributes.exif.manufacturer'), $exif->manufacturer);
                }
                if ($exif->model) {
                    $values[] = sprintf('%s: %s', trans('attributes.exif.model'), $exif->model);
                }
                if ($exif->exposure_time) {
                    $values[] = sprintf('%s: %s', trans('attributes.exif.exposure_time'), $exif->exposure_time);
                }
                if ($exif->aperture) {
                    $values[] = sprintf('%s: %s', trans('attributes.exif.aperture'), $exif->aperture);
                }
                if ($exif->iso) {
                    $values[] = sprintf('%s: %s', trans('attributes.exif.iso'), $exif->iso);
                }
                if ($exif->taken_at) {
                    $values[] = sprintf('%s: %s', trans('attributes.exif.taken_at'), $exif->taken_at);
                }
                return implode(', ', $values ?? []);
            },
            'file_url' => function () {
                $relativeUrl = $this->getPresenteeAttribute('thumbnails')->first()->relative_url ?? null;
                return $relativeUrl ? sprintf(config('format.storage.url.path'), $relativeUrl) : '';
            },
            'file_size' => function () {
                return Storage::size($this->getPresenteeAttribute('thumbnails')->first()->path);
            },
            'categories' => function () {
                $categories = $this->getPresenteeAttribute('tags')->pluck('value')->toArray();
                return $categories ? $categories : [];
            },
        ];
    }
}
