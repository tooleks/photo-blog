<?php

namespace App\Http\Controllers;

use Core\SiteMap\Contracts\SiteMap;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class SiteMapController.
 *
 * @property CacheManager cacheManager
 * @property SiteMap siteMap
 * @package App\Http\Controllers
 */
class SiteMapController extends Controller
{
    /**
     * SiteMapController constructor.
     *
     * @param CacheManager $cacheManager
     * @param SiteMap $siteMap
     */
    public function __construct(CacheManager $cacheManager, SiteMap $siteMap)
    {
        $this->cacheManager = $cacheManager;
        $this->siteMap = $siteMap;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()
            ->view('app.site-map.index', ['siteMap' => $this->siteMap->build()])
            ->header('Content-Type', 'text/xml');
    }
}
