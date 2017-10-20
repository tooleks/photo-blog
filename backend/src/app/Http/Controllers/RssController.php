<?php

namespace App\Http\Controllers;

use App\Services\Rss\Contracts\RssBuilderService;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;

/**
 * Class RssController.
 *
 * @package App\Http\Controllers
 */
class RssController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

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
     * @param ResponseFactory $responseFactory
     * @param RssBuilderService $rssBuilder
     * @param CacheManager $cacheManager
     * @param Config $config
     */
    public function __construct(ResponseFactory $responseFactory, RssBuilderService $rssBuilder, CacheManager $cacheManager, Config $config)
    {
        $this->responseFactory = $responseFactory;
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

        return $this->responseFactory
            ->view('app.rss.index', ['rss' => $rss])
            ->header('Content-Type', 'text/xml');
    }
}
