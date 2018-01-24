<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Resources\PhotoResource;
use App\Managers\Photo\Contracts\PhotoManager;
use App\Models\Photo;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class PhotosController.
 *
 * @package Api\V1\Http\Controllers
 */
class PhotosController extends Controller
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
     * PhotosController constructor.
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
     * @api {post} /v1/photos Create
     * @apiName Create
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {File{1KB..20MB}=JPEG} file Photo file.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "avg_color": "#000000",
     *     "created_at": "2099-12-31T23:59:59+00:00",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2099-12-31T23:59:59+00:00"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ]
     * }
     */

    /**
     * Create a photo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->merge(['created_by_user_id' => $request->user()->id]);

        $photo = $this->photoManager->create($request->all());

        return $this->responseFactory->json(new PhotoResource($photo), JsonResponse::HTTP_CREATED);
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
    public function delete($id): JsonResponse
    {
        $photo = $this->photoManager->getById((int) $id);

        $this->photoManager->delete($photo);

        return $this->responseFactory->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
