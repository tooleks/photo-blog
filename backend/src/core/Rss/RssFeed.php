<?php

namespace Core\Rss;

use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\Models\Photo;
use Core\Models\Tag;
use Core\Rss\Contracts\RssFeed as RssFeedContract;
use Lib\DataProvider\Criterias\Take;
use Lib\Rss\Contracts\RssBuilder;
use Lib\Rss\RssCategory;
use Lib\Rss\RssChannel;
use Lib\Rss\RssItem;
use Lib\Rss\RssEnclosure;

/**
 * Class RssService.
 *
 * @property RssBuilder rssBuilder
 * @property PhotoDataProvider photoDataProvider
 * @package Core\Rss
 */
class RssFeed implements RssFeedContract
{
    /**
     * RssService constructor.
     *
     * @param RssBuilder $rssBuilder
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(RssBuilder $rssBuilder, PhotoDataProvider $photoDataProvider)
    {
        $this->rssBuilder = $rssBuilder;
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function build(): RssBuilder
    {
        return $this->rssBuilder
            ->setChannel($this->provideChannel())
            ->setItems($this->provideItems());
    }

    /**
     * Provide the RSS channel.
     *
     * @return RssChannel
     */
    protected function provideChannel()
    {
        return (new RssChannel)
            ->setTitle(config('app.name'))
            ->setDescription(config('app.description'))
            ->setLink(url('/'));
    }

    /**
     * Provide the RSS items.
     *
     * @return array
     */
    protected function provideItems(): array
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria(new Take(50))
            ->get(['with' => ['thumbnails', 'tags']])
            ->map(function (Photo $photo) {
                return (new RssItem)
                    ->setTitle($photo->description)
                    ->setDescription('')
                    ->setLink(sprintf(config('format.frontend.url.photo_page'), $photo->id))
                    ->setEnclosure(
                        (new RssEnclosure)
                            ->setUrl(sprintf(config('format.storage.url.path'), $photo->thumbnails->first()->relative_url))
                            ->setType('image/jpeg')
                    )
                    ->setCategories(
                        $photo->tags
                            ->map(function (Tag $tag) {
                                return (new RssCategory)->setValue($tag->value);
                            })
                            ->toArray()
                    );
            })
            ->toArray();
    }
}
