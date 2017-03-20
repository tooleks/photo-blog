<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateSubscription;
use Core\DataServices\Subscription\Contracts\SubscriptionDataService;
use Core\Models\Subscription;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class SubscriptionController.
 *
 * @property SubscriptionDataService subscriptionDataService
 * @package Api\V1\Http\Controllers
 */
class SubscriptionController extends ResourceController
{
    /**
     * SubscriptionController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param string $presenterClass
     * @param SubscriptionDataService $subscriptionDataService
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        SubscriptionDataService $subscriptionDataService
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->subscriptionDataService = $subscriptionDataService;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/subscription Create
     * @apiName Create
     * @apiGroup Subscriptions
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email Subscriber email address.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "email": "username@mail.address",
     *          "token": "subscription_token_string"
     *      }
     *  }
     */

    /**
     * Create a resource.
     *
     * @param CreateSubscription $request
     * @return Subscription
     */
    public function create(CreateSubscription $request) : Subscription
    {
        $subscription = new Subscription;

        $subscription->generateToken();

        $this->subscriptionDataService->save($subscription, $request->all());

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
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     */

    /**
     * Delete a resource.
     *
     * @param Subscription $subscription
     * @return int
     */
    public function delete($subscription) : int
    {
        return $this->subscriptionDataService->delete($subscription);
    }
}
