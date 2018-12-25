<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Requests\PaginatedRequest;
use Api\V1\Http\Resources\PaginatedResource;
use Api\V1\Http\Resources\PostResource;
use Core\Contracts\PostManager;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

/**
 * Class PostPaginateAction.
 *
 * @package Api\V1\Http\Actions
 */
class PostPaginateAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var PostManager
     */
    private $postManager;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * PostPaginateAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param PostManager $postManager
     * @param CacheManager $cacheManager
     */
    public function __construct(ResponseFactory $responseFactory, PostManager $postManager, CacheManager $cacheManager)
    {
        $this->responseFactory = $responseFactory;
        $this->postManager = $postManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/posts?page=:page&per_page=:per_page Paginate
     * @apiName Paginate
     * @apiGroup Posts
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
     *             "id": 1,
     *             "created_by_user_id": 1,
     *             "is_published": true,
     *             "description": "The post description.",
     *             "published_at": "2099-12-31T23:59:59+00:00",
     *             "created_at": "2099-12-31T23:59:59+00:00",
     *             "updated_at": "2099-12-31T23:59:59+00:00",
     *             "photo": {
     *                 "id": 1,
     *                 "created_by_user_id" 1,
     *                 "avg_color": "#000000",
     *                 "created_at": "2099-12-31T23:59:59+00:00",
     *                 "exif": {
     *                     "manufacturer": "Manufacturer Name",
     *                     "model": "Model Number",
     *                     "exposure_time": "1/160",
     *                     "aperture": "f/11.0",
     *                     "iso": 200,
     *                     "taken_at": "2099-12-31T23:59:59+00:00",
     *                     "software": "Software Name"
     *                 },
     *                 "thumbnails": [
     *                     "medium": {
     *                         "url": "http://path/to/photo/thumbnail/medium_file"
     *                         "width": 500,
     *                         "height": 500
     *                     },
     *                     "large": {
     *                          "url": "http://path/to/photo/thumbnail/large_file"
     *                          "width": 1000,
     *                          "height": 1000
     *                     }
     *                 ]
     *             },
     *             "tags": [
     *                 {
     *                     "value": "tag",
     *                 }
     *             ]
     *         }
     *     ]
     * }
     */

    /**
     * Paginate over posts.
     *
     * @param PaginatedRequest $request
     * @return JsonResponse
     */
    public function __invoke(PaginatedRequest $request): JsonResponse
    {
        $posts = $this->postManager->paginate(
            $request->get('page', 1),
            $request->get('per_page', 20),
            $request->all()
        );

        return $this->responseFactory->json(new PaginatedResource($posts, PostResource::class), JsonResponse::HTTP_OK);
    }
}
