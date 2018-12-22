<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Resources\UserPlainResource;
use Core\Contracts\UserManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class UserGetByIdAction.
 *
 * @package Api\V1\Http\Actions
 */
class UserGetByIdAction
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
     * UserGetByIdAction constructor.
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
     * @api {get} /v1/users/:user_id Get
     * @apiName Get
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1...N}='me'} :user_id Unique resource ID.
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
     * Get a user.
     *
     * @param mixed $id
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        $user = $this->userManager->getById((int) $id);

        return $this->responseFactory->json(new UserPlainResource($user), Response::HTTP_OK);
    }
}
