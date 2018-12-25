<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Resources\PostResource;
use Core\Contracts\PostManager;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PostCreateAction.
 *
 * @package Api\V1\Http\Actions
 */
class PostCreateAction
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
     * PostCreateAction constructor.
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
     * @api {post} /v1/posts Create
     * @apiName Create
     * @apiGroup Posts
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "is_published": true,
     *     "description": "The post description.",
     *     "photo": {
     *         "id": 1
     *     },
     *     "tags": [
     *         {
     *             "value": "tag",
     *         }
     *     ]
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "created_by_user_id": 1,
     *     "is_published": true,
     *     "description": "The post description.",
     *     "published_at": "2099-12-31T23:59:59+00:00",
     *     "created_at": "2099-12-31T23:59:59+00:00",
     *     "updated_at": "2099-12-31T23:59:59+00:00",
     *     "photo": {
     *         "id": 1,
     *         "created_by_user_id" 1,
     *         "avg_color": "#000000",
     *         "created_at": "2099-12-31T23:59:59+00:00",
     *         "exif": {
     *             "manufacturer": "Manufacturer Name",
     *             "model": "Model Number",
     *             "exposure_time": "1/160",
     *             "aperture": "f/11.0",
     *             "iso": 200,
     *             "taken_at": "2099-12-31T23:59:59+00:00",
     *             "software": "Software Name"
     *         },
     *         "thumbnails": [
     *             "medium": {
     *                 "url": "http://path/to/photo/thumbnail/medium_file"
     *                 "width": 500,
     *                 "height": 500
     *             },
     *             "large": {
     *                  "url": "http://path/to/photo/thumbnail/large_file"
     *                  "width": 1000,
     *                  "height": 1000
     *             }
     *         ]
     *     },
     *     "tags": [
     *         {
     *             "value": "tag",
     *         }
     *     ]
     * }
     */

    /**
     * Create a post.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $post = $this->postManager->create($request->all());

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_CREATED);
    }
}
