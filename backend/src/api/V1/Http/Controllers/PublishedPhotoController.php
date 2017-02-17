<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreatePhoto;
use Api\V1\Http\Requests\FindPhoto;
use Api\V1\Http\Requests\UpdatePhoto;
use Core\DAL\Models\Photo;
use Core\DAL\DataServices\Photo\Criterias\IsPublished;
use Core\DAL\DataServices\Photo\Criterias\WhereSearchQuery;
use Core\DAL\DataServices\Photo\Criterias\WhereTag;
use Core\DAL\DataServices\Photo\Contracts\PhotoDataService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lib\DataService\Criterias\OrderByCreatedAt;
use Lib\DataService\Criterias\Skip;
use Lib\DataService\Criterias\Take;
use Throwable;

/**
 * Class PublishedPhotoController.
 *
 * @property PhotoDataService photoDataService
 * @package Api\V1\Http\Controllers
 */
class PublishedPhotoController extends ResourceController
{
    /**
     * PublishedPhotoController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param string $presenterClass
     * @param PhotoDataService $photoDataService
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        PhotoDataService $photoDataService
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->photoDataService = $photoDataService;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/published_photos Create
     * @apiName Create
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} photo_id Unique resource ID.
     * @apiParam {Integer{1..65535}} description Description.
     * @apiParam {Object[]} tags Tags collection.
     * @apiParam {String{1..255}} tags.text Tag text.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "user_id": 1,
     *          "absolute_url": "http://path/to/photo/file",
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "exif": {
     *              "manufacturer": "Manufacturer Name",
     *              "model": "Model Number",
     *              "exposure_time": "1/160",
     *              "aperture": "f/11.0",
     *              "iso": 200,
     *              "taken_at": "2016-10-24 12:24:33"
     *          },
     *          "thumbnails": [
     *              "medium": {
     *                  "absolute_url": "http://path/to/photo/thumbnail/medium_file"
     *                  "width": 500,
     *                  "height": 500
     *              },
     *              "large": {
     *                  "absolute_url": "http://path/to/photo/thumbnail/large_file"
     *                  "width": 1000,
     *                  "height": 1000
     *              }
     *          ],
     *          "tags": [
     *              {
     *                  "text": "lorem"
     *              },
     *              {
     *                  "text": "ipsum"
     *              }
     *          ]
     *      }
     *  }
     */

    /**
     * Create a photo.
     *
     * @param CreatePhoto $request
     * @return Photo
     * @throws Throwable
     */
    public function create(CreatePhoto $request) : Photo
    {
        $photo = $this->photoDataService
            ->applyCriteria(new IsPublished(false))
            ->getById($request->get('photo_id'));

        $photo->setIsPublishedAttribute(true);

        $this->photoDataService->save($photo, $request->all());

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/published_photos/:id Get
     * @apiName Get
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "user_id": 1,
     *          "absolute_url": "http://path/to/photo/file",
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "exif": {
     *              "manufacturer": "Manufacturer Name",
     *              "model": "Model Number",
     *              "exposure_time": "1/160",
     *              "aperture": "f/11.0",
     *              "iso": 200,
     *              "taken_at": "2016-10-24 12:24:33"
     *          },
     *          "thumbnails": [
     *              "medium": {
     *                  "absolute_url": "http://path/to/photo/thumbnail/medium_file"
     *                  "width": 500,
     *                  "height": 500
     *              },
     *              "large": {
     *                  "absolute_url": "http://path/to/photo/thumbnail/large_file"
     *                  "width": 1000,
     *                  "height": 1000
     *              }
     *          ],
     *          "tags": [
     *              {
     *                  "text": "lorem"
     *              },
     *              {
     *                  "text": "ipsum"
     *              }
     *          ]
     *      }
     *  }
     */

    /**
     * Get a photo.
     *
     * @param Photo $photo
     * @return Photo
     */
    public function get($photo) : Photo
    {
        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/published_photos Find
     * @apiName Find
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..100}} take
     * @apiParam {Integer{0..N}} skip
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": [
     *          {
     *              "id": 1,
     *              "user_id": 1,
     *              "absolute_url": "http://path/to/photo/file",
     *              "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *              "created_at": "2016-10-24 12:24:33",
     *              "updated_at": "2016-10-24 14:38:05",
     *              "exif": {
     *                  "manufacturer": "Manufacturer Name",
     *                  "model": "Model Number",
     *                  "exposure_time": "1/160",
     *                  "aperture": "f/11.0",
     *                  "iso": 200,
     *                  "taken_at": "2016-10-24 12:24:33"
     *              },
     *              "thumbnails": [
     *                  "medium": {
     *                      "absolute_url": "http://path/to/photo/thumbnail/medium_file"
     *                      "width": 500,
     *                      "height": 500
     *                  },
     *                  "large": {
     *                      "absolute_url": "http://path/to/photo/thumbnail/large_file"
     *                      "width": 1000,
     *                      "height": 1000
     *                  }
     *              ],
     *              "tags": [
     *                  {
     *                      "text": "lorem"
     *                  },
     *                  {
     *                      "text": "ipsum"
     *                  }
     *              ]
     *          }
     *      ]
     *  }
     */

    /**
     * Find photos.
     *
     * @param FindPhoto $request
     * @return Collection
     */
    public function find(FindPhoto $request) : Collection
    {
        $photos = $this->photoDataService
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria($request->has('tag') ? new WhereTag($request->get('tag')) : null)
            ->applyCriteria($request->has('query') ? new WhereSearchQuery($request->get('query')) : null)
            ->applyCriteria(new Skip($request->get('skip', 0)))
            ->applyCriteria(new Take($request->get('take', 10)))
            ->applyCriteria(new OrderByCreatedAt('desc'))
            ->get();

        return $photos;
    }

    /**
     * @apiVersion 1.0.0
     * @api {put} /v1/published_photos/:id Update
     * @apiName Update
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiParam {Integer{1..65535}} description Description.
     * @apiParam {Object[]} tags Tags collection.
     * @apiParam {String{1..255}} tags.text Tag text.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "user_id": 1,
     *          "absolute_url": "http://path/to/photo/file",
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "exif": {
     *              "manufacturer": "Manufacturer Name",
     *              "model": "Model Number",
     *              "exposure_time": "1/160",
     *              "aperture": "f/11.0",
     *              "iso": 200,
     *              "taken_at": "2016-10-24 12:24:33"
     *          },
     *          "thumbnails": [
     *              "medium": {
     *                  "absolute_url": "http://path/to/photo/thumbnail/medium_file"
     *                  "width": 500,
     *                  "height": 500
     *              },
     *              "large": {
     *                  "absolute_url": "http://path/to/photo/thumbnail/large_file"
     *                  "width": 1000,
     *                  "height": 1000
     *              }
     *          ],
     *          "tags": [
     *              {
     *                  "text": "lorem"
     *              },
     *              {
     *                  "text": "ipsum"
     *              }
     *          ]
     *      }
     *  }
     */

    /**
     * Update a photo.
     *
     * @param UpdatePhoto $request
     * @param Photo $photo
     * @return Photo
     * @throws Throwable
     */
    public function update(UpdatePhoto $request, $photo) : Photo
    {
        $this->photoDataService->save($photo, $request->all());

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/published_photos/:id Delete
     * @apiName Delete
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     */

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return int
     */
    public function delete($photo) : int
    {
        return $this->photoDataService->delete($photo);
    }
}
