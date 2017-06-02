<?php

namespace Api\V1\Presenters;

use Illuminate\Contracts\Container\Container;
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
     * The container implementation.
     *
     * @var Container
     */
    protected $container;

    /**
     * PublishedPhotoPresenter constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'id' => 'id',
            'created_by_user_id' => 'created_by_user_id',
            'url' => function () {
                $relativeUrl = $this->getWrappedModelAttribute('relative_url');
                return $relativeUrl ? sprintf(config('format.storage.url.path'), $relativeUrl) : null;
            },
            'avg_color' => 'avg_color',
            'description' => 'description',
            'created_at' => function () {
                return (string) $this->getWrappedModelAttribute('created_at') ?? null;
            },
            'updated_at' => function () {
                return (string) $this->getWrappedModelAttribute('updated_at') ?? null;
            },
            'exif' => function () {
                return $this->container
                    ->make(ExifPresenter::class)
                    ->setWrappedModel($this->getWrappedModelAttribute('exif'));
            },
            'thumbnails' => function () {
                $thumbnails = collect($this->getWrappedModelAttribute('thumbnails'))->present(ThumbnailPresenter::class);
                return [
                    'medium' => $thumbnails->get(0, []),
                    'large' => $thumbnails->get(1, []),
                ];
            },
            'tags' => function () {
                return collect($this->getWrappedModelAttribute('tags'))->present(TagPresenter::class);
            },
        ];
    }
}
