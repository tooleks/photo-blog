<?php

namespace App\Services\SiteMap;

use App\Managers\Photo\Contracts\PhotoManager;
use App\Managers\Tag\Contracts\TagManager;
use App\Models\Photo;
use App\Models\Tag;
use App\Services\SiteMap\Contracts\SiteMapBuilderService as SiteMapBuilderServiceContract;
use Lib\SiteMap\Contracts\Builder;
use Lib\SiteMap\Item;

/**
 * Class SiteMapBuilderService.
 *
 * @package App\Services\SiteMap
 */
class SiteMapBuilderService implements SiteMapBuilderServiceContract
{
    /**
     * @var Builder
     */
    private $siteMapBuilder;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * SiteMapBuilderService constructor.
     *
     * @param Builder $siteMapBuilder
     * @param PhotoManager $photoManager
     * @param TagManager $tagManager
     */
    public function __construct(Builder $siteMapBuilder, PhotoManager $photoManager, TagManager $tagManager)
    {
        $this->siteMapBuilder = $siteMapBuilder;
        $this->photoManager = $photoManager;
        $this->tagManager = $tagManager;
    }

    /**
     * @inheritdoc
     */
    public function build(): Builder
    {
        $items[] = (new Item)
            ->setLocation(config('main.frontend.url'))
            ->setChangeFrequency('daily')
            ->setPriority('1');

        $items[] = (new Item)
            ->setLocation(sprintf(config('format.frontend.url.path'), 'about-me'))
            ->setChangeFrequency('weekly')
            ->setPriority('0.6');

        $items[] = (new Item)
            ->setLocation(sprintf(config('format.frontend.url.path'), 'contact-me'))
            ->setChangeFrequency('weekly')
            ->setPriority('0.6');

        $items[] = (new Item)
            ->setLocation(sprintf(config('format.frontend.url.path'), 'subscription'))
            ->setChangeFrequency('weekly')
            ->setPriority('0.5');

        $this->photoManager->eachPublished(function (Photo $photo) use (&$items) {
            $items[] = (new Item)
                ->setLocation(sprintf(config('format.frontend.url.photo_page'), $photo->id))
                ->setLastModified($photo->updated_at->toAtomString())
                ->setChangeFrequency('weekly')
                ->setPriority('0.8');
        });

        $this->tagManager->each(function (Tag $tag) use (&$items) {
            $items[] = (new Item)
                    ->setLocation(sprintf(config('format.frontend.url.tag_page'), $tag->value))
                    ->setChangeFrequency('daily')
                    ->setPriority('0.7');
        });

        $this->siteMapBuilder->setItems($items);

        return $this->siteMapBuilder;
    }
}
