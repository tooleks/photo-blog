<?php

namespace App\Http\Controllers;

use Core\SiteMap\Contracts\SiteMap;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class SiteMapController.
 *
 * @property SiteMap siteMap
 * @package App\Http\Controllers
 */
class SiteMapController extends Controller
{
    /**
     * SiteMapController constructor.
     *
     * @param SiteMap $siteMap
     */
    public function __construct(SiteMap $siteMap)
    {
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
