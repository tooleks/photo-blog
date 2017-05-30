<?php

namespace App\Http\Controllers;

use Core\Rss\Contracts\RssFeed;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class RssController.
 *
 * @property RssFeed rssFeed
 * @package App\Http\Controllers
 */
class RssController extends Controller
{
    /**
     * RssController constructor.
     *
     * @param RssFeed $rssFeed
     */
    public function __construct(RssFeed $rssFeed)
    {
        $this->rssFeed = $rssFeed;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()
            ->view('app.rss.index', ['rss' => $this->rssFeed->build()])
            ->header('Content-Type', 'text/xml');
    }
}
