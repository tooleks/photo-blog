<?php

namespace App\Http\Controllers;

use Core\Services\Rss\Contracts\RssBuilderService;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class RssController.
 *
 * @property RssBuilderService rssBuilder
 * @package App\Http\Controllers
 */
class RssController extends Controller
{
    /**
     * RssController constructor.
     *
     * @param RssBuilderService $rssBuilder
     */
    public function __construct(RssBuilderService $rssBuilder)
    {
        $this->rssBuilder = $rssBuilder;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()
            ->view('app.rss.index', ['rss' => $this->rssBuilder->run()])
            ->header('Content-Type', 'text/xml');
    }
}
