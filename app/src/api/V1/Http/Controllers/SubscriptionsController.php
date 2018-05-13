<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\PaginatedRequest;
use Api\V1\Http\Requests\ReCaptchaRequest;
use Api\V1\Http\Resources\PaginatedResource;
use Api\V1\Http\Resources\SubscriptionPlainResource;
use App\Managers\Subscription\Contracts\SubscriptionManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class SubscriptionsController.
 *
 * @package Api\V1\Http\Controllers
 */
class SubscriptionsController extends Controller
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
     * SubscriptionController constructor.
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
     * @api {post} /v1/subscriptions Create
     * @apiName Create
     * @apiGroup Subscriptions
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "email": "username@domain.name"
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "email": "username@mail.address",
     *     "token": "subscription_token_string"
     * }
     */

    /**
     * Create a subscription.
     *
     * @param ReCaptchaRequest $request
     * @return JsonResponse
     */
    public function create(ReCaptchaRequest $request): JsonResponse
    {
        $subscription = $this->subscriptionManager->create($request->all());

        return $this->responseFactory->json(new SubscriptionPlainResource($subscription), JsonResponse::HTTP_CREATED);
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
    public function paginate(PaginatedRequest $request): JsonResponse
    {
        $paginator = $this->subscriptionManager->paginate(
            $request->get('page', 1),
            $request->get('per_page', 20),
            $request->query()
        );

        return $this->responseFactory->json(new PaginatedResource($paginator, SubscriptionPlainResource::class), JsonResponse::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/subscriptions/:token Delete
     * @apiName Delete
     * @apiGroup Subscriptions
     * @apiHeader {String} Accept application/json
     * @apiParam {String{1..255}} :token Subscription token.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a subscription.
     *
     * @param string $token
     * @return JsonResponse
     */
    public function delete(string $token): JsonResponse
    {
        $subscription = $this->subscriptionManager->getByToken($token);

        $this->subscriptionManager->delete($subscription);

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
