<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Proxy\Contracts\OAuthProxy;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AuthDeleteAction.
 *
 * @package Api\V1\Http\Actions
 */
class AuthDeleteAction
{
    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var OAuthProxy
     */
    protected $oAuthProxy;

    /**
     * AuthDeleteAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param OAuthProxy $oAuthProxy
     */
    public function __construct(ResponseFactory $responseFactory, OAuthProxy $oAuthProxy)
    {
        $this->responseFactory = $responseFactory;
        $this->oAuthProxy = $oAuthProxy;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/auth/token Delete
     * @apiName Delete
     * @apiGroup Auth
     * @apiHeader {String} Accept application/json
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete an auth token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();

        return $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);
    }
}
