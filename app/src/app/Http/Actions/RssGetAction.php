<?php

namespace App\Http\Actions;

use App\Services\Rss\Contracts\RssBuilder;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

/**
 * Class RssGetAction.
 *
 * @package App\Http\Actions
 */
class RssGetAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var RssBuilder
     */
    private $rssBuilder;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * RssGetAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param RssBuilder $rssBuilder
     * @param CacheManager $cacheManager
     * @param Config $config
     */
    public function __construct(ResponseFactory $responseFactory, RssBuilder $rssBuilder, CacheManager $cacheManager, Config $config)
    {
        $this->responseFactory = $responseFactory;
        $this->rssBuilder = $rssBuilder;
        $this->cacheManager = $cacheManager;
        $this->config = $config;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $rss = $this->cacheManager
            ->tags(['rss', 'posts', 'photos', 'tags'])
            ->remember('rss', $this->config->get('cache.lifetime'), function () {
                return $this->rssBuilder->build();
            });

        return $this->responseFactory
            ->view('app.rss.index', ['rss' => $rss])
            ->header('Content-Type', 'text/xml');
    }
}
