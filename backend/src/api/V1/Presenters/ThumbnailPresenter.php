<?php

namespace Api\V1\Presenters;

use Illuminate\Contracts\Filesystem\Factory as Storage;
use Tooleks\Laravel\Presenter\Presenter;

/**
 * Class TokenPresenter.
 *
 * @property string absolute_url
 * @property int width
 * @property int height
 * @package Api\V1\Presenters
 */
class ThumbnailPresenter extends Presenter
{
    /**
     * The storage implementation.
     *
     * @var Storage
     */
    protected $storage;

    /**
     * ThumbnailPresenter constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributesMap(): array
    {
        return [
            'url' => function (): ?string {
                $url = $this->storage->url($this->getWrappedModelAttribute('path'));
                return $url ? sprintf(config('format.storage.url.path'), $url) : null;
            },
            'width' => 'width',
            'height' => 'height',
        ];
    }
}
