<?php

namespace App\Http\Controllers;

use Core\Services\Rss\Contracts\RssBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class RssController.
 *
 * @package App\Http\Controllers
 */
class RssController extends Controller
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var RssBuilderService
     */
    private $rssBuilder;

    /**
     * RssController constructor.
     *
     * @param CacheManager $cacheManager
     * @param RssBuilderService $rssBuilder
     */
    public function __construct(CacheManager $cacheManager, RssBuilderService $rssBuilder)
    {
        $this->cacheManager = $cacheManager;
        $this->rssBuilder = $rssBuilder;
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
