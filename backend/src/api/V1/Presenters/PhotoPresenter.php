<?php

namespace Api\V1\Presenters;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Support\Collection;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class PhotoPresenter.
 *
 * @property int id
 * @property int created_by_user_id
 * @property string absolute_url
 * @property string avg_color
 * @property string created_at
 * @property string updated_at
 * @property ExifPresenter exif
 * @property Collection thumbnails
 * @package Api\V1\Presenters
 */
class PhotoPresenter extends Presenter
{
    /**
     * The container implementation.
     *
     * @var Container
     */
    protected $container;

    /**
     * The storage implementation.
     *
     * @var Storage
     */
    protected $storage;

    /**
     * PhotoPresenter constructor.
     *
     * @param Container $container
     * @param Storage $storage
     */
    public function __construct(Container $container, Storage $storage)
    {
        $this->container = $container;
        $this->storage = $storage;
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
                $url = $this->storage->url($this->getWrappedModelAttribute('path'));
                return $url ? sprintf(config('format.storage.url.path'), $url) : null;
            },
            'avg_color' => 'avg_color',
            'created_at' => function () {
                return (string) $this->getWrappedModelAttribute('created_at') ?? null;
            },
            'updated_at' => function () {
                return (string) $this->getWrappedModelAttribute('updated_at') ?? null;
            },
            'exif' => function () {
                return $this->container
                    ->make(ExifPresenter::class)
                    ->setWrappedModel($this->getWrappedModelAttribute('exif') ?? []);
            },
            'thumbnails' => function () {
                $thumbnails = collect($this->getWrappedModelAttribute('thumbnails'));
                return [
                    'medium' => $this->container
                        ->make(ThumbnailPresenter::class)
                        ->setWrappedModel($thumbnails->get(0, [])),
                    'large' => $this->container
                        ->make(ThumbnailPresenter::class)
                        ->setWrappedModel($thumbnails->get(1, [])),
                ];
            },
        ];
    }
}
