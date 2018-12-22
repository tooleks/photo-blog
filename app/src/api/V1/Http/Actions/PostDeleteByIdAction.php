<?php

namespace Api\V1\Http\Actions;

use Core\Contracts\PostManager;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

/**
 * Class PostDeleteByIdAction.
 *
 * @package Api\V1\Http\Actions
 */
class PostDeleteByIdAction
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
     * PostDeleteByIdAction constructor.
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
    public function __invoke($id): JsonResponse
    {
        $this->postManager->deleteById((int) $id);

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
