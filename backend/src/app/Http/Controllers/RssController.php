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
 * @property CacheManager cacheManager
 * @package App\Http\Controllers
 */
class RssController extends Controller
{
    /**
     * RssController constructor.
     *
     * @param RssBuilderService $rssBuilder
     * @param CacheManager $cacheManager
     */
    public function __construct(RssBuilderService $rssBuilder, CacheManager $cacheManager)
    {
        $this->rssBuilder = $rssBuilder;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $rss = $this->cacheManager
            ->tags(['rss', 'photos', 'tags'])
            ->remember('rss', config('cache.lifetime'), function () {
                return $this->rssBuilder->run();
            });

        return response()
            ->view('app.rss.index', compact('rss'))
            ->header('Content-Type', 'text/xml');
    }
}
