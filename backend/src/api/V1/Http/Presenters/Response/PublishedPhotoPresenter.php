<?php

namespace Api\V1\Http\Presenters\Response;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Factory as Storage;
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
 * @package Api\V1\Http\Presenters\Response
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
     * The storage implementation.
     *
     * @var Storage
     */
    protected $storage;

    /**
     * PublishedPhotoPresenter constructor.
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
            'url' => function (): ?string {
                $url = $this->storage->url($this->getWrappedModelAttribute('path'));
                return $url ? sprintf(config('format.storage.url.path'), $url) : null;
            },
            'avg_color' => 'avg_color',
            'description' => function (): ?string {
                return htmlspecialchars($this->getWrappedModelAttribute('description'), ENT_QUOTES);
            },
            'created_at' => function (): ?string {
                return $this->getWrappedModelAttribute('created_at');
            },
            'updated_at' => function (): ?string {
                return $this->getWrappedModelAttribute('updated_at');
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
