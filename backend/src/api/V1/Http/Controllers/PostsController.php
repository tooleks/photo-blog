<?php

namespace Api\V1\Http\Controllers;

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
     * Create a post.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->merge(['created_by_user_id' => $request->user()->id]);

        $post = $this->postManager->create($request->all());

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_CREATED);
    }

    /**
     * Update a post.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $this->postManager->update($post, $request->all());

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_OK);
    }

    /**
     * Get a post.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function get(Post $post): JsonResponse
    {
        return $this->responseFactory->json(new PostResource($post), JsonResponse::HTTP_OK);
    }

    /**
     * Paginate over posts.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function paginate(Request $request): JsonResponse
    {
        $posts = $this->postManager->paginate(
            $request->get('page', 1),
            $request->get('per_page', 10),
            $request->all()
        );

        return $this->responseFactory->json(new PaginatedResource($posts, PostResource::class), JsonResponse::HTTP_OK);
    }

    /**
     * Delete a post.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function delete(Post $post): JsonResponse
    {
        $this->postManager->delete($post);

        $this->cacheManager->tags(['posts', 'photos', 'tags'])->flush();

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
