<?php

namespace App\Services\Rss;

use function App\Util\url_frontend_photo;
use function App\Util\url_storage;
use App\Models\Post;
use App\Managers\Photo\Contracts\PhotoManager;
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
        return (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->whereIsPublished()
            ->orderByCreatedAtDesc()
            ->take(100)
            ->get()
            ->map(function (Post $post) {
                return (new Item)
                    ->setTitle($post->description)
                    ->setDescription($post->photo->exif->toString())
                    ->setLink(url_frontend_photo($post->id))
                    ->setGuid(url_frontend_photo($post->id))
                    ->setPubDate($post->photo->created_at->toAtomString())
                    ->setEnclosure(
                        (new Enclosure)
                            ->setUrl(url_storage($this->storage->url($post->photo->thumbnails->first()->path)))
                            ->setType('image/jpeg')
                            ->setLength($this->storage->size($post->photo->thumbnails->first()->path))
                    )
                    ->setCategories(
                        $post->tags
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
