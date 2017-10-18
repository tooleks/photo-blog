<?php

namespace App\Services\Rss;

use function App\Util\url_frontend_photo;
use function App\Util\url_storage;
use App\Managers\Photo\Contracts\PhotoManager;
use App\Models\Photo;
use App\Services\Rss\Contracts\RssBuilderService as RssBuilderServiceContract;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Lib\Rss\Contracts\Builder;
use Lib\Rss\Category;
use Lib\Rss\Channel;
use Lib\Rss\Item;
use Lib\Rss\Enclosure;

/**
 * Class RssBuilderService.
 *
 * @package App\Services\Rss
 */
class RssBuilderService implements RssBuilderServiceContract
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var Builder
     */
    private $rssBuilder;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * RssBuilderService constructor.
     *
     * @param Storage $storage
     * @param Builder $rssBuilder
     * @param PhotoManager $photoManager
     */
    public function __construct(Storage $storage, Builder $rssBuilder, PhotoManager $photoManager)
    {
        $this->storage = $storage;
        $this->rssBuilder = $rssBuilder;
        $this->photoManager = $photoManager;
    }

    /**
     * Provide the RSS channel.
     *
     * @return Channel
     */
    private function provideChannel(): Channel
    {
        return (new Channel)
            ->setTitle(config('app.name'))
            ->setDescription(config('app.description'))
            ->setLink(url('/'));
    }

    /**
     * Provide the RSS items.
     *
     * @return array
     */
    private function provideItems(): array
    {
        return $this->photoManager
            ->getNewlyPublished(50)
            ->map(function (Photo $photo) {
                $url = url_frontend_photo($photo->id);
                $enclosureUrl = url_storage($this->storage->url($photo->thumbnails->first()->path));
                $enclosureSize = $this->storage->size($photo->thumbnails->first()->path);
                return (new Item)
                    ->setTitle($photo->description)
                    ->setDescription($photo->exif->toString())
                    ->setLink($url)
                    ->setGuid($url)
                    ->setPubDate($photo->created_at)
                    ->setEnclosure(
                        (new Enclosure)
                            ->setUrl($enclosureUrl)
                            ->setType('image/jpeg')
                            ->setLength($enclosureSize)
                    )
                    ->setCategories(
                        $photo->tags
                            ->pluck('value')
                            ->map(function (string $value) {
                                return (new Category)->setValue($value);
                            })
                            ->toArray()
                    );
            })
            ->toArray();
    }

    /**
     * @inheritdoc
     */
    public function build(): Builder
    {
        return $this->rssBuilder
            ->setChannel($this->provideChannel())
            ->setItems($this->provideItems());
    }
}
