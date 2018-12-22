<?php

namespace App\Http\Actions;

use App\Services\SiteMap\Contracts\SiteMapBuilder;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

/**
 * Class SiteMapGetAction.
 *
 * @package App\Http\Actions
 */
class SiteMapGetAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var SiteMapBuilder
     */
    private $siteMapBuilder;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * SiteMapGetAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param SiteMapBuilder $siteMapBuilder
     * @param CacheManager $cacheManager
     * @param Config $config
     */
    public function __construct(ResponseFactory $responseFactory, SiteMapBuilder $siteMapBuilder, CacheManager $cacheManager, Config $config)
    {
        $this->responseFactory = $responseFactory;
        $this->siteMapBuilder = $siteMapBuilder;
        $this->cacheManager = $cacheManager;
        $this->config = $config;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $siteMap = $this->cacheManager
            ->tags(['siteMap', 'posts', 'photos', 'tags'])
            ->remember('siteMap', $this->config->get('cache.lifetime'), function () {
                return $this->siteMapBuilder->build();
            });

        return $this->responseFactory
            ->view('app.site-map.index', ['siteMap' => $siteMap])
            ->header('Content-Type', 'text/xml');
    }
}
