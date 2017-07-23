<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Proxy\Contracts\OAuthProxy;
use Api\V1\Http\Requests\CreateTokenRequest;
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
     * HTTP/1.1 201 Created
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
        return $this->oAuthProxy->requestTokenByCredentials(
            env('OAUTH_CLIENT_ID'),
            $request->get('email'),
            $request->get('password')
        );
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/auth/token Delete Token
     * @apiName Delete Token
     * @apiGroup Auth
     * @apiHeader {String} Accept application/json
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a token.
     *
     * @param Request $request
     * @return void
     */
    public function deleteToken(Request $request): void
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
    }
}
