<?php

namespace Core\SiteMap;

use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\DataProviders\Tag\Contracts\TagDataProvider;
use Core\Models\Photo;
use Core\Models\Tag;
use Core\SiteMap\Contracts\SiteMap as SiteMapContract;
use Lib\SiteMap\Contracts\SiteMapBuilder;
use Lib\SiteMap\SiteMapItem;

/**
 * Class SiteMap.
 *
 * @property SiteMapBuilder siteMapBuilder
 * @property PhotoDataProvider photoDataProvider
 * @property TagDataProvider tagDataProvider
 * @package Core\SiteMap
 */
class SiteMap implements SiteMapContract
{
    /**
     * SiteMap constructor.
     *
     * @param SiteMapBuilder $siteMapBuilder
     * @param PhotoDataProvider $photoDataProvider
     * @param TagDataProvider $tagDataProvider
     */
    public function __construct(
        SiteMapBuilder $siteMapBuilder,
        PhotoDataProvider $photoDataProvider,
        TagDataProvider $tagDataProvider
    )
    {
        $this->siteMapBuilder = $siteMapBuilder;
        $this->photoDataProvider = $photoDataProvider;
        $this->tagDataProvider = $tagDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function build(): SiteMapBuilder
    {
        $this->siteMapBuilder->addItem(
            (new SiteMapItem)
                ->setLocation(config('main.frontend.url'))
                ->setChangeFrequency('daily')
                ->setPriority('1')
        );

        $this->siteMapBuilder->addItem(
            (new SiteMapItem)
                ->setLocation(sprintf(config('format.frontend.url.path'), 'about-me'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.6')
        );

        $this->siteMapBuilder->addItem(
            (new SiteMapItem)
                ->setLocation(sprintf(config('format.frontend.url.path'), 'contact-me'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.6')
        );

        $this->siteMapBuilder->addItem(
            (new SiteMapItem)
                ->setLocation(sprintf(config('format.frontend.url.path'), 'subscription'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.5')
        );

        $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->each(function (Photo $photo) {
                $this->siteMapBuilder->addItem(
                    (new SiteMapItem)
                        ->setLocation(sprintf(config('format.frontend.url.photo_page'), $photo->id))
                        ->setLastModified($photo->updated_at->tz('UTC')->toAtomString())
                        ->setChangeFrequency('weekly')
                        ->setPriority('0.8')
                );
            });

        $this->tagDataProvider
            ->each(function (Tag $tag) {
                $this->siteMapBuilder->addItem(
                    (new SiteMapItem)
                        ->setLocation(sprintf(config('format.frontend.url.tag_page'), $tag->value))
                        ->setChangeFrequency('daily')
                        ->setPriority('0.7')
                );
            });

        return $this->siteMapBuilder;
    }
}
