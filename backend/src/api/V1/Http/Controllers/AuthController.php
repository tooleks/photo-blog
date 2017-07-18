<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Proxy\Contracts\OAuthProxy;
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
     * @var OAuthProxy
     */
    protected $oAuthProxy;

    /**
     * AuthController constructor.
     *
     * @param OAuthProxy $oAuthProxy
     */
    public function __construct(OAuthProxy $oAuthProxy)
    {
        $this->oAuthProxy = $oAuthProxy;
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
     * {
     *     "token_type": "Bearer",
     *     "expires_in": "3600",
     *     "access_token": "access_token",
     *     "refresh_token": "refresh_token",
     * }
     */

    /**
     * Create token.
     *
     * @param CreateTokenRequest $request
     * @return Response
     */
    public function createToken(CreateTokenRequest $request)
    {
        return $this->oAuthProxy->requestTokenByCredentials(
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
     * {
     *     "token_type": "Bearer",
     *     "expires_in": "3600",
     *     "access_token": "access_token",
     *     "refresh_token": "refresh_token",
     * }
     */

    /**
     * Create refresh token.
     *
     * @param CreateRefreshTokenRequest $request
     * @return Response
     */
    public function createRefreshToken(CreateRefreshTokenRequest $request)
    {
        return $this->oAuthProxy->requestTokenByRefreshToken(
            env('OAUTH_CLIENT_ID'),
            $request->get('refresh_token')
        );
    }
}