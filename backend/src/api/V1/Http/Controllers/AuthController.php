<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Proxy\Contracts\OAuthProxy;
use Api\V1\Http\Requests\CreateAuthRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class AuthController.
 *
 * @package Api\V1\Http\Controllers
 */
class AuthController extends Controller
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
     * AuthController constructor.
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
     * @api {post} /v1/auth/token Create
     * @apiName Create
     * @apiGroup Auth
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "email": "username@domain.name",
     *     "password": "password"
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Create an auth.
     *
     * @param CreateAuthRequest $request
     * @return Response
     */
    public function create(CreateAuthRequest $request)
    {
        return $this->oAuthProxy->requestTokenByCredentials(
            env('OAUTH_CLIENT_ID'),
            $request->input('email'),
            $request->input('password')
        );
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
     * Delete an auth.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();

        return $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);
    }
}
