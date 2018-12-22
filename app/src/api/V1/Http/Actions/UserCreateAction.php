<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Resources\UserPlainResource;
use Core\Contracts\UserManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class UserCreateAction.
 *
 * @package Api\V1\Http\Actions
 */
class UserCreateAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UserCreateAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param UserManager $userManager
     */
    public function __construct(ResponseFactory $responseFactory, UserManager $userManager)
    {
        $this->responseFactory = $responseFactory;
        $this->userManager = $userManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/users Create
     * @apiName Create
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "name": "username",
     *     "email": "username@domain.name",
     *     "password": "password"
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "name": "username",
     *     "email": "username@mail.address",
     *     "role": "Customer",
     *     "created_at": "2099-12-31T23:59:59+00:00",
     *     "updated_at": "2099-12-31T23:59:59+00:00"
     * }
     */

    /**
     * Create a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->userManager->create($request->all());

        return $this->responseFactory->json(new UserPlainResource($user), Response::HTTP_CREATED);
    }
}
