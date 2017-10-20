<?php

namespace App\Http\Controllers;

use App\Services\SiteMap\Contracts\SiteMapBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class SiteMapController.
 *
 * @package App\Http\Controllers
 */
class SiteMapController extends Controller
{
    /**
     * @var SiteMapBuilderService
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
     * SiteMapController constructor.
     *
     * @param SiteMapBuilderService $siteMapBuilder
     * @param CacheManager $cacheManager
     * @param Config $config
     */
    public function __construct(SiteMapBuilderService $siteMapBuilder, CacheManager $cacheManager, Config $config)
    {
        $this->siteMapBuilder = $siteMapBuilder;
        $this->cacheManager = $cacheManager;
        $this->config = $config;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $siteMap = $this->cacheManager
            ->tags(['siteMap', 'photos', 'tags'])
            ->remember('siteMap', $this->config->get('cache.lifetime'), function () {
                return $this->siteMapBuilder->build();
            });

        return response()
            ->view('app.site-map.index', ['siteMap' => $siteMap])
            ->header('Content-Type', 'text/xml');
    }
}
