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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $this->cacheManager->remember("request:{$request->getRequestUri()}", 15, function () {
            return ['siteMap' => $this->siteMap->build()];
        });

        return response()
            ->view('app.site-map.index', $data)
            ->header('Content-Type', 'text/xml');
    }
}
