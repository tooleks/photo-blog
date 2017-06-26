<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateTokenRequest;
use Core\Managers\User\Contracts\UserManager;
use Core\Models\User;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Routing\Controller;

/**
 * Class TokenController.
 *
 * @package Api\V1\Http\Controllers
 */
class TokenController extends Controller
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * TokenController constructor.
     *
     * @param Auth $auth
     * @param UserManager $userManager
     */
    public function __construct(Auth $auth, UserManager $userManager)
    {
        $this->auth = $auth;
        $this->userManager = $userManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/token Create
     * @apiName Create
     * @apiGroup Token
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email User's email address.
     * @apiParam {String{1..255}} password User's password.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "user_id": 1,
     *     "api_token": "user_api_token_string"
     * }
     */

    /**
     * Create a token.
     *
     * @param CreateTokenRequest $request
     * @return User
     */
    public function create(CreateTokenRequest $request): User
    {
        $user = $this->userManager->getByCredentials($request->get('email'), $request->get('password'));

        $this->userManager->generateApiToken($user);
        $this->userManager->save($user);

        $this->auth->setUser($user);

        return $user;
    }
}
