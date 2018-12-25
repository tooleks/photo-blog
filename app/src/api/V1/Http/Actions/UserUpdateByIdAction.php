<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Resources\UserPlainResource;
use Core\Contracts\UserManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class UserUpdateByIdAction.
 *
 * @package Api\V1\Http\Actions
 */
class UserUpdateByIdAction
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
     * UserUpdateByIdAction constructor.
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
     * @api {put} /v1/users/:user_id Update
     * @apiName Update
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1...N}='me'} :user_id Unique resource ID.
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "name": "username",
     *     "email": "username@domain.name",
     *     "password": "password"
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 20O OK
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
     * Update a user.
     *
     * @param mixed $id
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke($id, Request $request): JsonResponse
    {
        $user = $this->userManager->updateById((int) $id, $request->all());

        return $this->responseFactory->json(new UserPlainResource($user), Response::HTTP_OK);
    }
}
