<?php

namespace App\Http\Controllers;

use Core\Services\Rss\Contracts\RssBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
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
     * @param CacheManager $cacheManager
     * @return Response
     */
    public function index(CacheManager $cacheManager)
    {
        $rss = $cacheManager->remember('rss', 10, function () {
            return $this->rssBuilder->run();
        });

        return response()
            ->view('app.rss.index', compact('rss'))
            ->header('Content-Type', 'text/xml');
    }
}
