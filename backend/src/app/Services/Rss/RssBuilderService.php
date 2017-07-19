<?php

namespace App\Services\Rss;

use App\Managers\Photo\Contracts\PhotoManager;
use App\Services\Rss\Presenters\PhotoPresenter;
use App\Services\Rss\Contracts\RssBuilderService as RssBuilderServiceContract;
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
     * @param Builder $rssBuilder
     * @param PhotoManager $photoManager
     */
    public function __construct(Builder $rssBuilder, PhotoManager $photoManager)
    {
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
            ->getLastPublished(50)
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
