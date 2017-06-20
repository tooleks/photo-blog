<?php

namespace App\Http\Controllers;

use Core\Services\SiteMap\Contracts\SiteMapBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class SiteMapController.
 *
 * @property SiteMapBuilderService siteMapBuilder
 * @property CacheManager cacheManager
 * @package App\Http\Controllers
 */
class SiteMapController extends Controller
{
    /**
     * SiteMapController constructor.
     *
     * @param SiteMapBuilderService $siteMapBuilder
     * @param CacheManager $cacheManager
     */
    public function __construct(SiteMapBuilderService $siteMapBuilder, CacheManager $cacheManager)
    {
        $this->siteMapBuilder = $siteMapBuilder;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $siteMap = $this->cacheManager
            ->tags(['siteMap', 'photos', 'tags'])
            ->remember('siteMap', config('cache.lifetime'), function () {
                return $this->siteMapBuilder->run();
            });

        return response()
            ->view('app.site-map.index', compact('siteMap'))
            ->header('Content-Type', 'text/xml');
    }
}
