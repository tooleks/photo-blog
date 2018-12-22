<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Proxy\Contracts\OAuthProxy;
use Api\V1\Http\Requests\CreateAuthRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

/**
 * Class AuthCreateAction.
 *
 * @package Api\V1\Http\Actions
 */
class AuthCreateAction
{
    /**
     * @var OAuthProxy
     */
    protected $oAuthProxy;

    /**
     * AuthCreateAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param OAuthProxy $oAuthProxy
     */
    public function __construct(OAuthProxy $oAuthProxy)
    {
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
     * Create an auth token.
     *
     * @param CreateAuthRequest $request
     * @return Response
     */
    public function __invoke(CreateAuthRequest $request)
    {
        return $this->oAuthProxy->requestTokenByCredentials(
            env('OAUTH_CLIENT_ID'),
            $request->input('email'),
            $request->input('password')
        );
    }
}
