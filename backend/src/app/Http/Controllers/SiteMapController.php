<?php

namespace App\Http\Controllers;

use Core\Services\SiteMap\Contracts\SiteMapBuilderService;
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
     * @return Response
     */
    public function index()
    {
        return response()
            ->view('app.site-map.index', ['siteMap' => $this->siteMapBuilder->run()])
            ->header('Content-Type', 'text/xml');
    }
}
