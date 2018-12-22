<?php

namespace Api\V1\Http\Actions;

use Core\Contracts\PhotoManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

/**
 * Class PhotoDeleteByIdAction.
 *
 * @package Api\V1\Http\Actions
 */
class PhotoDeleteByIdAction
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var PhotoManager
     */
    private $photoManager;

    /**
     * PhotoDeleteByIdAction constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param PhotoManager $photoManager
     */
    public function __construct(ResponseFactory $responseFactory, PhotoManager $photoManager)
    {
        $this->responseFactory = $responseFactory;
        $this->photoManager = $photoManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/photos/:photo_id Delete
     * @apiName Delete
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {Integer{1..N}} :photo_id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a photo.
     *
     * @param mixed $id
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        $this->photoManager->deleteById((int) $id);

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
