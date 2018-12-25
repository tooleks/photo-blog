<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Requests\PaginatedRequest;
use Api\V1\Http\Resources\PaginatedResource;
use Api\V1\Http\Resources\SubscriptionPlainResource;
use Core\Contracts\SubscriptionManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

/**
 * Class SubscriptionPaginateAction.
 *
 * @package Api\V1\Http\Actions
 */
class SubscriptionPaginateAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * SubscriptionPaginateAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(ResponseFactory $responseFactory, SubscriptionManager $subscriptionManager)
    {
        $this->responseFactory = $responseFactory;
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/subscriptions?page=:page&per_page=:per_page Paginate
     * @apiName Paginate
     * @apiGroup Subscriptions
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
     *             "email": "username@mail.address",
     *             "token": "subscription_token_string"
     *         }
     *     ]
     * }
     */

    /**
     * @param PaginatedRequest $request
     * @return JsonResponse
     */
    public function __invoke(PaginatedRequest $request): JsonResponse
    {
        $paginator = $this->subscriptionManager->paginate(
            $request->get('page', 1),
            $request->get('per_page', 20),
            $request->query()
        );

        return $this->responseFactory->json(new PaginatedResource($paginator, SubscriptionPlainResource::class), JsonResponse::HTTP_OK);
    }
}
