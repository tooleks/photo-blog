<?php

namespace Core\Services\SiteMap;

use Core\DataProviders\Tag\Contracts\TagDataProvider;
use Core\Managers\Photo\Contracts\PhotoManager;
use Core\Models\Photo;
use Core\Models\Tag;
use Core\Services\SiteMap\Contracts\SiteMapBuilderService as SiteMapBuilderServiceContract;
use Lib\SiteMap\Contracts\Builder;
use Lib\SiteMap\Item;

/**
 * Class SiteMapBuilderService.
 *
 * @package Core\Services\SiteMap
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
     * @var TagDataProvider
     */
    private $tagDataProvider;

    /**
     * SiteMapBuilderService constructor.
     *
     * @param Builder $siteMapBuilder
     * @param PhotoManager $photoManager
     * @param TagDataProvider $tagDataProvider
     */
    public function __construct(Builder $siteMapBuilder, PhotoManager $photoManager, TagDataProvider $tagDataProvider)
    {
        $this->siteMapBuilder = $siteMapBuilder;
        $this->photoManager = $photoManager;
        $this->tagDataProvider = $tagDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function build(): Builder
    {
        $this->siteMapBuilder->addItem(
            (new Item)
                ->setLocation(config('main.frontend.url'))
                ->setChangeFrequency('daily')
                ->setPriority('1')
        );

        $this->siteMapBuilder->addItem(
            (new Item)
                ->setLocation(sprintf(config('format.frontend.url.path'), 'about-me'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.6')
        );

        $this->siteMapBuilder->addItem(
            (new Item)
                ->setLocation(sprintf(config('format.frontend.url.path'), 'contact-me'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.6')
        );

        $this->siteMapBuilder->addItem(
            (new Item)
                ->setLocation(sprintf(config('format.frontend.url.path'), 'subscription'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.5')
        );

        $this->photoManager->eachPublished(function (Photo $photo) {
            $this->siteMapBuilder->addItem(
                (new Item)
                    ->setLocation(sprintf(config('format.frontend.url.photo_page'), $photo->id))
                    ->setLastModified($photo->updated_at->toAtomString())
                    ->setChangeFrequency('weekly')
                    ->setPriority('0.8')
            );
        });

        $this->tagDataProvider
            ->each(function (Tag $tag) {
                $this->siteMapBuilder->addItem(
                    (new Item)
                        ->setLocation(sprintf(config('format.frontend.url.tag_page'), $tag->value))
                        ->setChangeFrequency('daily')
                        ->setPriority('0.7')
                );
            });

        return $this->siteMapBuilder;
    }
}
