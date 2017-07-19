<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateSubscriptionRequest;
use App\Managers\Subscription\Contracts\SubscriptionManager;
use App\Models\Subscription;
use Illuminate\Routing\Controller;

/**
 * Class SubscriptionsController.
 *
 * @package Api\V1\Http\Controllers
 */
class SubscriptionsController extends Controller
{
    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * SubscriptionController constructor.
     *
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/subscriptions Create
     * @apiName Create
     * @apiGroup Subscriptions
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email Subscriber email address.
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
     * @param CreateSubscriptionRequest $request
     * @return Subscription
     */
    public function create(CreateSubscriptionRequest $request): Subscription
    {
        $subscription = $this->subscriptionManager->generateByEmail($request->get('email'));

        return $subscription;
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
     * @param Subscription $subscription
     * @return void
     */
    public function delete(Subscription $subscription): void
    {
        $this->subscriptionManager->delete($subscription);
    }
}
