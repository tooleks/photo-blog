<?php

namespace App\Services\SiteMap;

use function App\Util\url_frontend;
use function App\Util\url_frontend_photo;
use function App\Util\url_frontend_tag;
use App\Models\Post;
use App\Managers\Photo\Contracts\PhotoManager;
use App\Managers\Tag\Contracts\TagManager;
use App\Models\Tag;
use App\Services\SiteMap\Contracts\SiteMapBuilderService as SiteMapBuilderServiceContract;
use Lib\SiteMap\Contracts\Builder;
use Lib\SiteMap\Item;
use Illuminate\Support\Collection;

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
        $items = collect();

        $items->push(
            (new Item)
                ->setLocation(url_frontend())
                ->setChangeFrequency('daily')
                ->setPriority('1')
        );

        $items->push(
            (new Item)
                ->setLocation(url_frontend('/about-me'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.6')
        );

        $items->push(
            (new Item)
                ->setLocation(url_frontend('/contact-me'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.6')
        );

        $items->push(
            (new Item)
                ->setLocation(url_frontend('/subscription'))
                ->setChangeFrequency('weekly')
                ->setPriority('0.5')
        );

        (new Post)
            ->newQuery()
            ->whereIsPublished()
            ->chunk(50, function (Collection $posts) use ($items) {
                $posts->each(function (Post $post) use ($items) {
                    $items->push(
                        (new Item)
                            ->setLocation(url_frontend_photo($post->id))
                            ->setLastModified($post->updated_at->toAtomString())
                            ->setChangeFrequency('weekly')
                            ->setPriority('0.8')
                    );
                });
            });

        (new Tag)
            ->newQuery()
            ->whereHasPosts()
            ->chunk(50, function (Collection $tags) use ($items) {
                $tags->each(function (Tag $tag) use ($items) {
                    $items->push(
                        (new Item)
                            ->setLocation(url_frontend_tag($tag->value))
                            ->setChangeFrequency('daily')
                            ->setPriority('0.7')
                    );
                });
            });

        $this->siteMapBuilder->setItems($items->toArray());

        return $this->siteMapBuilder;
    }
}
