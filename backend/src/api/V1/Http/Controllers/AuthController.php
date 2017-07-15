<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateRefreshTokenRequest;
use Api\V1\Http\Requests\CreateTokenRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Application;
use Laravel\Passport\ClientRepository as OAuthClientRepository;

/**
 * Class AuthController.
 *
 * @package Api\V1\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var OAuthClientRepository
     */
    protected $oAuthClientRepository;

    /**
     * AuthController constructor.
     *
     * @param Application $application
     * @param OAuthClientRepository $oAuthClientRepository
     */
    public function __construct(Application $application, OAuthClientRepository $oAuthClientRepository)
    {
        $this->application = $application;
        $this->oAuthClientRepository = $oAuthClientRepository;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createToken(CreateTokenRequest $request)
    {
        $proxy = Request::create('oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => env('OAUTH_CLIENT_ID'),
            'client_secret' => $this->oAuthClientRepository->findActive(env('OAUTH_CLIENT_ID'))->secret,
            'scope' => '*',
            'username' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        return $this->application->handle($proxy);
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createRefreshToken(CreateRefreshTokenRequest $request)
    {
        $proxy = Request::create('oauth/token', 'POST', [
            'grant_type' => 'refresh_token',
            'client_id' => env('OAUTH_CLIENT_ID'),
            'client_secret' => $this->oAuthClientRepository->findActive(env('OAUTH_CLIENT_ID'))->secret,
            'scope' => '*',
            'refresh_token' => $request->get('refresh_token'),
        ]);

        return $this->application->handle($proxy);
    }
}
