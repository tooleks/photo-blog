<?php

namespace App\Http\Controllers;

use App\Services\SiteMap\Contracts\SiteMapBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
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
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var SiteMapBuilderService
     */
    private $siteMapBuilder;

    /**
     * SiteMapController constructor.
     *
     * @param CacheManager $cacheManager
     * @param SiteMapBuilderService $siteMapBuilder
     */
    public function __construct(CacheManager $cacheManager, SiteMapBuilderService $siteMapBuilder)
    {
        $this->cacheManager = $cacheManager;
        $this->siteMapBuilder = $siteMapBuilder;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $siteMap = $this->cacheManager
            ->tags(['siteMap', 'photos', 'tags'])
            ->remember('siteMap', config('cache.lifetime'), function () {
                return $this->siteMapBuilder->build();
            });

        return response()
            ->view('app.site-map.index', compact('siteMap'))
            ->header('Content-Type', 'text/xml');
    }
}
