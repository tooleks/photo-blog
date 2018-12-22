<?php

namespace Api\V1\Http\Actions;

use Core\Contracts\UserManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class UserDeleteByIdAction.
 *
 * @package Api\V1\Http\Actions
 */
class UserDeleteByIdAction
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
     * UserDeleteByIdAction constructor.
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
     * @api {delete} /v1/users/:user_id Delete
     * @apiName Delete
     * @apiGroup Users
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1...N}='me'} :user_id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a user.
     *
     * @param mixed $id
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        $this->userManager->deleteById((int) $id);

        return $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);
    }
}
