<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateTokenRequest;
use Core\Models\User;
use Core\DataServices\User\Contracts\UserDataService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class TokenController.
 *
 * @property UserDataService userDataService
 * @package Api\V1\Http\Controllers
 */
class TokenController extends ResourceController
{
    /**
     * TokenController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param string $presenterClass
     * @param UserDataService $userDataService
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        UserDataService $userDataService
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->userDataService = $userDataService;
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
    public function create(CreateTokenRequest $request) : User
    {
        $user = $this->userDataService->getByCredentials($request->get('email'), $request->get('password'));

        $user->generateApiToken();

        $this->userDataService->save($user);

        $this->guard->setUser($user);

        return $user;
    }
}
