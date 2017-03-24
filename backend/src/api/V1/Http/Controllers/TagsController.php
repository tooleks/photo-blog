<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\FindTagsRequest;
use Core\DataServices\Tag\Contracts\TagDataService;
use Core\DataServices\Tag\Criterias\SortByPhotosCount;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;

/**
 * Class TagsController.
 *
 * @property TagDataService tagDataService
 * @package Api\V1\Http\Controllers
 */
class TagsController extends ResourceController
{
    /**
     * TagsController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param string $presenterClass
     * @param TagDataService $tagDataService
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        TagDataService $tagDataService
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->tagDataService = $tagDataService;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /api/v1/tags Find
     * @apiName Find
     * @apiGroup Tags
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{0..N}} [page=1]
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
     *             "text": "nature"
     *         }
     *     ]
     *  }
     */

    /**
     * Find photos.
     *
     * @param FindTagsRequest $request
     * @return AbstractPaginator
     */
    public function find(FindTagsRequest $request) : AbstractPaginator
    {
        $paginator = $this->tagDataService
            ->applyCriteria((new SortByPhotosCount)->desc())
            ->paginate($request->get('page', 1), $request->get('per_page', 20));

        $paginator->appends($request->query());

        return $paginator;
    }
}
