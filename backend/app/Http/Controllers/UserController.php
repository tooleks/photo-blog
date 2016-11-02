<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\AuthenticateUserRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Models\User;
use App\Services\Users\Contracts\UserServiceContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @property UserServiceContract userService
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param UserServiceContract $userService
     */
    public function __construct(Request $request, Guard $guard, UserServiceContract $userService)
    {
        parent::__construct($request, $guard);

        $this->userService = $userService;
    }

    /**
     * Authenticate user.
     *
     * @apiVersion 1.0.0
     * @api {post} user Authenticate User
     * @apiName AuthenticateUser
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String} email E-mail address.
     * @apiParam {String} password Password.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "Lorem",
     *          "email": "ipsum@domain.name",
     *          "api_token": "Tw992Z7WXF4v6217Qt3ixzmduc4lCZxXVjz6zdnIxlXvWLgyzDraFLmkQTr9PyjZ",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": {
     *              "name": "User"
     *          }
     *      }
     *  }
     *
     * @param AuthenticateUserRequest $request
     * @return Authenticatable
     */
    public function authenticate(AuthenticateUserRequest $request)
    {
        $user = $this->userService->getByCredentials($request->all());

        $this->guard->setUser($user);

        return $user;
    }

    /**
     * Get user.
     *
     * @apiVersion 1.0.0
     * @api {get} user/:id Get User
     * @apiName GetUser
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "Lorem",
     *          "email": "ipsum@domain.name",
     *          "api_token": "Tw992Z7WXF4v6217Qt3ixzmduc4lCZxXVjz6zdnIxlXvWLgyzDraFLmkQTr9PyjZ",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": {
     *              "name": "User"
     *          }
     *      }
     *  }
     *
     * @param User $user
     * @return User
     */
    public function view(User $user)
    {
        return $user;
    }

    /**
     * Create user.
     *
     * @apiVersion 1.0.0
     * @api {post} user Create User
     * @apiName CreateUser
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} name User name.
     * @apiParam {String{1..255}} email E-mail address (unique).
     * @apiParam {String{1..255}} password Password.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "Lorem",
     *          "email": "ipsum@domain.name",
     *          "api_token": "Tw992Z7WXF4v6217Qt3ixzmduc4lCZxXVjz6zdnIxlXvWLgyzDraFLmkQTr9PyjZ",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": {
     *              "name": "User"
     *          }
     *      }
     *  }
     *
     * @param CreateUserRequest $request
     * @return User
     */
    public function create(CreateUserRequest $request)
    {
        $user = $this->userService
            ->setScenario('create')
            ->create($request->all());

        return $user;
    }

    /**
     * Update user.
     *
     * @apiVersion 1.0.0
     * @api {put} user/:id Update User
     * @apiName UpdateUser
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {Integer{1..N}} :id Unique ID.
     * @apiParam {String{1..255}} name User name.
     * @apiParam {String{1..255}} email E-mail address (unique).
     * @apiParam {String{1..255}} password Password.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "Lorem",
     *          "email": "ipsum@domain.name",
     *          "api_token": "Tw992Z7WXF4v6217Qt3ixzmduc4lCZxXVjz6zdnIxlXvWLgyzDraFLmkQTr9PyjZ",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": {
     *              "name": "User"
     *          }
     *      }
     *  }
     *
     * @param User $user
     * @return User
     */
    public function update(User $user)
    {
        $user = $this->userService
            ->setScenario('update')
            ->save($user, $this->request->all());

        return $user;
    }

    /**
     * Delete user.
     *
     * @apiVersion 1.0.0
     * @api {delete} user/:id Delete User
     * @apiName DeleteUser
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {Integer{1..N}} :id Unique ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        $result = $this->userService->delete($user);

        return $result;
    }
}
