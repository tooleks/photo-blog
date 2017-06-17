<?php

namespace Core\Rss;

use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\Rss\Contracts\RssFeed as RssFeedContract;
use Core\Rss\Presenters\PhotoPresenter;
use Lib\DataProvider\Criterias\SortByCreatedAt;
use Lib\DataProvider\Criterias\Take;
use Lib\Rss\Contracts\Builder;
use Lib\Rss\Category;
use Lib\Rss\Channel;
use Lib\Rss\Item;
use Lib\Rss\Enclosure;

/**
 * Class RssService.
 *
 * @property Builder rssBuilder
 * @property PhotoDataProvider photoDataProvider
 * @package Core\Rss
 */
class RssFeed implements RssFeedContract
{
    /**
     * RssService constructor.
     *
     * @param Builder $rssBuilder
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(Builder $rssBuilder, PhotoDataProvider $photoDataProvider)
    {
        $this->rssBuilder = $rssBuilder;
        $this->photoDataProvider = $photoDataProvider;
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

    /**
     * Provide the RSS channel.
     *
     * @return Channel
     */
    protected function provideChannel(): Channel
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
    protected function provideItems(): array
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->applyCriteria(new Take(50))
            ->get(['with' => ['exif', 'thumbnails', 'tags']])
            ->present(PhotoPresenter::class)
            ->map(function (PhotoPresenter $photo) {
                return (new Item)
                    ->setTitle($photo->title)
                    ->setDescription($photo->description)
                    ->setLink($photo->url)
                    ->setGuid($photo->url)
                    ->setPubDate($photo->published_date)
                    ->setEnclosure(
                        (new Enclosure)
                            ->setUrl($photo->file_url)
                            ->setType('image/jpeg')
                            ->setLength($photo->file_size)
                    )
                    ->setCategories(
                        array_map(function ($value) {
                            return (new Category)->setValue($value);
                        }, $photo->categories)
                    );
            })
            ->toArray();
    }
}
