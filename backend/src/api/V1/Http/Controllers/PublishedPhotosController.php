<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreatePublishedPhotoRequest;
use Api\V1\Http\Requests\PaginatedRequest;
use Api\V1\Http\Resources\PaginatedResource;
use Api\V1\Http\Resources\PhotoResource;
use App\Managers\Photo\Contracts\PhotoManager;
use App\Models\Photo;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class PublishedPhotosController.
 *
 * @package Api\V1\Http\Controllers
 */
class PublishedPhotosController extends Controller
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
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * PublishedPhotosController constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param PhotoManager $photoManager
     * @param CacheManager $cacheManager
     */
    public function __construct(ResponseFactory $responseFactory, PhotoManager $photoManager, CacheManager $cacheManager)
    {
        $this->responseFactory = $responseFactory;
        $this->photoManager = $photoManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/published_photos/:photo_id Create
     * @apiName Create
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} :photo_id Unique resource ID.
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "photo_id": 1,
     *     "description": "The message description.",
     *     "tags": [
     *         {
     *             "value": "sky"
     *         }
     *     ]
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
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
     *     ],
     *     "tags": [
     *         {
     *             "value": "sky"
     *         }
     *     ]
     * }
     */

    /**
     * Create a photo.
     *
     * @param CreatePublishedPhotoRequest $request
     * @return JsonResponse
     */
    public function create(CreatePublishedPhotoRequest $request): JsonResponse
    {
        $photo = $this->photoManager->getUnpublishedById($request->get('photo_id'));

        $request->merge(['is_published' => true]);

        $this->photoManager->saveByAttributes($photo, $request->all());

        $this->cacheManager->tags(['photos', 'tags'])->flush();

        return $this->responseFactory->json(new PhotoResource($photo), Response::HTTP_CREATED);
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/published_photos/:photo_id Get
     * @apiName Get
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :photo_id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
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
     *     ],
     *     "tags": [
     *         {
     *             "value": "sky"
     *         }
     *     ]
     * }
     */

    /**
     * Get a photo.
     *
     * @param Photo $photo
     * @return JsonResponse
     */
    public function get(Photo $photo): JsonResponse
    {
        return $this->responseFactory->json(new PhotoResource($photo), Response::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/published_photos Find
     * @apiName Find
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} [page=1]
     * @apiParam {Integer{1..100}} [per_page=20]
     * @apiParam {String{1..255}} [tag] Tag to search by.
     * @apiParam {String{1..255}} [search_phrase] Search phrase to search by.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "total": 100,
     *     "per_page": 10,
     *     "current_page": 2,
     *     "last_page": 10,
     *     "next_page_url": "http://path/to/api/resource?page=3",
     *     "prev_page_url": "http://path/to/api/resource?page=1",
     *     "from": 10,
     *     "to": 20,
     *     "data": [
     *         {
     *             "id": 1,
     *             "created_by_user_id" 1,
     *             "avg_color": "#000000",
     *             "created_at": "2016-10-24 12:24:33",
     *             "updated_at": "2016-10-24 14:38:05",
     *             "exif": {
     *                 "manufacturer": "Manufacturer Name",
     *                 "model": "Model Number",
     *                 "exposure_time": "1/160",
     *                 "aperture": "f/11.0",
     *                 "iso": 200,
     *                 "taken_at": "2016-10-24 12:24:33"
     *             },
     *             "thumbnails": [
     *                 "medium": {
     *                     "url": "http://path/to/photo/thumbnail/medium_file"
     *                     "width": 500,
     *                     "height": 500
     *                 },
     *                 "large": {
     *                      "url": "http://path/to/photo/thumbnail/large_file"
     *                      "width": 1000,
     *                      "height": 1000
     *                 }
     *             ],
     *             "tags": [
     *                 {
     *                     "value": "sky"
     *                 }
     *             ]
     *         }
     *     ]
     * }
     */

    /**
     * Find photos.
     *
     * @param PaginatedRequest $request
     * @return JsonResponse
     */
    public function find(PaginatedRequest $request): JsonResponse
    {
        $photos = $this->photoManager->paginateOverNewlyPublished(
            $request->get('page', 1),
            $request->get('per_page', 20),
            $request->all()
        );

        return $this->responseFactory->json(new PaginatedResource($photos, PhotoResource::class), Response::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {put} /v1/published_photos/:photo_id Update
     * @apiName Update
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} :photo_id Unique resource ID.
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "description": "The message description.",
     *     "tags": [
     *         {
     *             "value": "sky"
     *         }
     *     ]
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
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
     *     ],
     *     "tags": [
     *         {
     *             "value": "sky"
     *         }
     *     ]
     * }
     */

    /**
     * Update a photo.
     *
     * @param Request $request
     * @param Photo $photo
     * @return JsonResponse
     */
    public function update(Request $request, Photo $photo): JsonResponse
    {
        $this->photoManager->saveByAttributes($photo, $request->all());

        $this->cacheManager->tags(['photos', 'tags'])->flush();

        return $this->responseFactory->json(new PhotoResource($photo), Response::HTTP_OK);
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/published_photos/:photo_id Delete
     * @apiName Delete
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :photo_id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return JsonResponse
     */
    public function delete(Photo $photo): JsonResponse
    {
        $this->photoManager->delete($photo);

        $this->cacheManager->tags(['photos', 'tags'])->flush();

        return $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);
    }
}
