<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateTokenRequest;
use Core\Models\User;
use Core\DataProviders\User\Contracts\UserDataProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Controller;

/**
 * Class TokenController.
 *
 * @property Guard guard
 * @property UserDataProvider userDataProvider
 * @package Api\V1\Http\Controllers
 */
class TokenController extends Controller
{
    /**
     * TokenController constructor.
     *
     * @param Guard $guard
     * @param UserDataProvider $userDataProvider
     */
    public function __construct(Guard $guard, UserDataProvider $userDataProvider)
    {
        $this->guard = $guard;
        $this->userDataProvider = $userDataProvider;
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
        $user = $this->userDataProvider->getByCredentials($request->get('email'), $request->get('password'));

        $user->generateApiToken();

        $this->userDataProvider->save($user);

        $this->guard->setUser($user);

        return $user;
    }
}
