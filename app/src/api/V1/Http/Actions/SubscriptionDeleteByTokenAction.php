<?php

namespace Api\V1\Http\Actions;

use Core\Contracts\SubscriptionManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

/**
 * Class SubscriptionDeleteByTokenAction.
 *
 * @package Api\V1\Http\Actions
 */
class SubscriptionDeleteByTokenAction
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
     * SubscriptionDeleteByTokenAction constructor.
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
    public function __invoke(string $token): JsonResponse
    {
        $this->subscriptionManager->deleteByToken($token);

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
