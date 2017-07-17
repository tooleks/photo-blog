<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Proxy\Contracts\AuthorizationProxy;
use Api\V1\Http\Requests\CreateRefreshTokenRequest;
use Api\V1\Http\Requests\CreateTokenRequest;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController.
 *
 * @package Api\V1\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var AuthorizationProxy
     */
    protected $authorizationProxy;

    /**
     * AuthController constructor.
     *
     * @param AuthorizationProxy $authorizationProxy
     */
    public function __construct(AuthorizationProxy $authorizationProxy)
    {
        $this->authorizationProxy = $authorizationProxy;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/auth/token Create Token
     * @apiName Create Token
     * @apiGroup Auth
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email User's email address.
     * @apiParam {String{1..255}} password User's password.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {}
     */

    /**
     * Create token.
     *
     * @param CreateTokenRequest $request
     * @return Response
     */
    public function createToken(CreateTokenRequest $request)
    {
        return $this->authorizationProxy->authorizeWithCredentials(
            env('OAUTH_CLIENT_ID'),
            $request->get('email'),
            $request->get('password')
        );
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/auth/refresh-token Create Refresh Token
     * @apiName Create Refresh Token
     * @apiGroup Auth
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..N}} refresh_token User's refresh token.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {}
     */

    /**
     * Create refresh token.
     *
     * @param CreateRefreshTokenRequest $request
     * @return Response
     */
    public function createRefreshToken(CreateRefreshTokenRequest $request)
    {
        return $this->authorizationProxy->authorizeWithRefreshToken(
            env('OAUTH_CLIENT_ID'),
            $request->get('refresh_token')
        );
    }
}
