<?php

namespace App\Http\Controllers;

use App\Services\Rss\Contracts\RssBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Config\Repository as Config;
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
     * @var RssBuilderService
     */
    private $rssBuilder;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * RssController constructor.
     *
     * @param RssBuilderService $rssBuilder
     * @param CacheManager $cacheManager
     * @param Config $config
     */
    public function __construct(RssBuilderService $rssBuilder, CacheManager $cacheManager, Config $config)
    {
        $this->rssBuilder = $rssBuilder;
        $this->cacheManager = $cacheManager;
        $this->config = $config;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $rss = $this->cacheManager
            ->tags(['rss', 'photos', 'tags'])
            ->remember('rss', $this->config->get('cache.lifetime'), function () {
                return $this->rssBuilder->build();
            });

        return response()
            ->view('app.rss.index', ['rss' => $rss])
            ->header('Content-Type', 'text/xml');
    }
}
