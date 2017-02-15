<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateToken;
use Core\DAL\Models\User;
use Core\DAL\Repositories\User\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class TokenController.
 *
 * @property UserRepository $userRepository
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
     * @param UserRepository $userRepository
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        UserRepository $userRepository
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->userRepository = $userRepository;
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
     *  {
     *      "status": true,
     *      "data": {
     *          "user_id": 1,
     *          "api_token": "user_api_token_string"
     *      }
     *  }
     *
     *
     * Create a token.
     *
     * @param CreateToken $request
     * @return User
     */
    public function create(CreateToken $request) : User
    {
        $user = $this->userRepository->getByCredentials($request->get('email'), $request->get('password'));

        $user->generateApiToken();

        $this->userRepository->save($user);

        $this->guard->setUser($user);

        return $user;
    }
}
