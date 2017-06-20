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
 * @package App\Http\Controllers
 */
class SiteMapController extends Controller
{
    /**
     * SiteMapController constructor.
     *
     * @param SiteMapBuilderService $siteMapBuilder
     */
    public function __construct(SiteMapBuilderService $siteMapBuilder)
    {
        $this->siteMapBuilder = $siteMapBuilder;
    }

    /**
     * @param CacheManager $cacheManager
     * @return Response
     */
    public function index(CacheManager $cacheManager)
    {
        $siteMap = $cacheManager->remember('siteMap', 10, function () {
            return $this->siteMapBuilder->run();
        });

        return response()
            ->view('app.site-map.index', compact('siteMap'))
            ->header('Content-Type', 'text/xml');
    }
}
