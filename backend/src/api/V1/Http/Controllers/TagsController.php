<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\FindTagsRequest;
use Core\DataProviders\Tag\Contracts\TagDataProvider;
use Core\DataProviders\Tag\Criterias\SortByPhotosCount;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller;

/**
 * Class TagsController.
 *
 * @property TagDataProvider tagDataProvider
 * @property CacheManager cacheManager
 * @package Api\V1\Http\Controllers
 */
class TagsController extends Controller
{
    /**
     * TagsController constructor.
     *
     * @param TagDataProvider $tagDataProvider
     * @param CacheManager $cacheManager
     */
    public function __construct(TagDataProvider $tagDataProvider, CacheManager $cacheManager)
    {
        $this->tagDataProvider = $tagDataProvider;
        $this->cacheManager = $cacheManager;
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
                return $this->tagDataProvider
                    ->applyCriteria((new SortByPhotosCount)->desc())
                    ->getPaginator($request->get('page', 1), $request->get('per_page', 20));
            });

        $paginator->appends($request->query());

        return $paginator;
    }
}
