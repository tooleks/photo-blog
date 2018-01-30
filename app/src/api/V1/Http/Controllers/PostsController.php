<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\PaginatedRequest;
use Api\V1\Http\Resources\PaginatedResource;
use Api\V1\Http\Resources\PostResource;
use App\Managers\Post\Contracts\PostManager;
use App\Models\Post;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class PostsController.
 *
 * @package Api\V1\Http\Controllers
 */
class PostsController extends Controller
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
     * PostsController constructor.
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
     *             "taken_at": "2099-12-31T23:59:59+00:00"
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
    public function create(Request $request): JsonResponse
    {
        $post = $this->postManager->create($request->all());

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_CREATED);
    }

    /**
     * @apiVersion 1.0.0
     * @api {put} /v1/posts/:post_id Update
     * @apiName Update
     * @apiGroup Posts
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} :post_id Unique resource ID.
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
     *             "taken_at": "2099-12-31T23:59:59+00:00"
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
     * Update a post.
     *
     * @param mixed $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $post = $this->postManager->getById((int) $id);

        $this->postManager->update($post, $request->all());

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/posts/:post_id Get
     * @apiName Get
     * @apiGroup Posts
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :post_id Unique resource ID.
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
     *             "taken_at": "2099-12-31T23:59:59+00:00"
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
     * Get a post.
     *
     * @param mixed $id
     * @param Request $request
     * @return JsonResponse
     */
    public function get($id, Request $request): JsonResponse
    {
        $post = $this->postManager->getById((int) $id, $request->all());

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/posts/:post_id/previous Get Previous
     * @apiName Get Previous
     * @apiGroup Posts
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :post_id Unique resource ID.
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
     *             "taken_at": "2099-12-31T23:59:59+00:00"
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
     * Get previous post.
     *
     * @param mixed $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getPrevious($id, Request $request): JsonResponse
    {
        $post = $this->postManager->getBeforeId((int) $id, $request->all());

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/posts/:post_id/next Get Next
     * @apiName Get Next
     * @apiGroup Posts
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :post_id Unique resource ID.
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
     *             "taken_at": "2099-12-31T23:59:59+00:00"
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
     * Get next post.
     *
     * @param mixed $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getNext($id, Request $request): JsonResponse
    {
        $post = $this->postManager->getAfterId((int) $id, $request->all());

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_OK);
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
     *                     "taken_at": "2099-12-31T23:59:59+00:00"
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
    public function paginate(PaginatedRequest $request): JsonResponse
    {
        $posts = $this->postManager->paginate(
            $request->get('page', 1),
            $request->get('per_page', 20),
            $request->all()
        );

        return $this->responseFactory->json(new PaginatedResource($posts, PostResource::class), JsonResponse::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/posts/:post_id Delete
     * @apiName Delete
     * @apiGroup Posts
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :post_id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a post.
     *
     * @param mixed $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $post = $this->postManager->getById((int) $id);

        $this->postManager->delete($post);

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
