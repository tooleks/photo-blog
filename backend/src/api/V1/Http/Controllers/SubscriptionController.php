<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateSubscription;
use Core\DataServices\Subscription\Contracts\SubscriptionDataService;
use Core\Models\Subscription;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class SubscriptionController.
 *
 * @property SubscriptionDataService subscriptionDataService
 * @package Api\V1\Http\Controllers
 */
class SubscriptionController extends Controller
{
    /**
     * SubscriptionController constructor.
     *
     * @param SubscriptionDataService $subscriptionDataService
     */
    public function __construct(SubscriptionDataService $subscriptionDataService)
    {
        $this->subscriptionDataService = $subscriptionDataService;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/subscription Create
     * @apiName Create
     * @apiGroup Subscription
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email Subscriber email address.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": null
     *  }
     */

    /**
     * Create a resource.
     *
     * @param CreateSubscription $request
     * @return Response
     */
    public function create(CreateSubscription $request)
    {
        $subscription = new Subscription;

        $this->subscriptionDataService->save($subscription, $request->all());

        return new Response(null, Response::HTTP_CREATED);
    }
}
