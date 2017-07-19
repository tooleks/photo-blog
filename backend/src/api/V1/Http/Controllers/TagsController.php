<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\FindTagsRequest;
use App\Managers\Tag\Contracts\TagManager;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller;

/**
 * Class TagsController.
 *
 * @package Api\V1\Http\Controllers
 */
class TagsController extends Controller
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * TagsController constructor.
     *
     * @param CacheManager $cacheManager
     * @param TagManager $tagManager
     */
    public function __construct(CacheManager $cacheManager, TagManager $tagManager)
    {
        $this->cacheManager = $cacheManager;
        $this->tagManager = $tagManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/tags Find
     * @apiName Find
     * @apiGroup Tags
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} [page=1]
     * @apiParam {Integer{1..100}} [per_page=20]
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "total": 100,
     *     "per_page": 10,
     *     "current_page": 2,
     *     "last_page": 10,
     *     "next_page_url": "http://path/to/api/resource?page=3",
     *     "prev_page_url": "http://path/to/api/resource?page=1",
     *     "from": 10,
     *     "to": 20,
     *     "data": [
     *         {
     *             "value": "nature"
     *         }
     *     ]
     * }
     */

    /**
     * Find photos.
     *
     * @param FindTagsRequest $request
     * @return AbstractPaginator
     */
    public function find(FindTagsRequest $request): AbstractPaginator
    {
        $cacheKey = sprintf(
            'tags:%s:%s',
            $request->get('page'),
            $request->get('per_page')
        );

        $paginator = $this->cacheManager
            ->tags(['tags'])
            ->remember($cacheKey, config('cache.lifetime'), function () use ($request) {
                return $this->tagManager->paginateOverMostPopular($request->get('page', 1), $request->get('per_page', 20));
            });

        $paginator->appends($request->query());

        return $paginator;
    }
}
