<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreatePhoto;
use Api\V1\Http\Requests\FindPhoto;
use Api\V1\Http\Requests\UpdatePhoto;
use Core\DAL\Models\Photo;
use Core\DAL\Repositories\Photo\Criterias\IsPublished;
use Core\DAL\Repositories\Photo\Criterias\WhereSearchQuery;
use Core\DAL\Repositories\Photo\Criterias\WhereTag;
use Core\DAL\Repositories\Photo\PhotoRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lib\Repositories\Criterias\OrderBy;
use Lib\Repositories\Criterias\Skip;
use Lib\Repositories\Criterias\Take;
use Throwable;

/**
 * Class PublishedPhotoController.
 *
 * @property PhotoRepository photoRepository
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
     * @param PhotoRepository $photoRepository
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        PhotoRepository $photoRepository
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->photoRepository = $photoRepository;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/photo Create
     * @apiName Create
     * @apiGroup Photo
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} uploaded_photo_id Unique resource ID.
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
     *
     *
     * Create a photo.
     *
     * @param CreatePhoto $request
     * @return Photo
     * @throws Throwable
     */
    public function create(CreatePhoto $request) : Photo
    {
        $photo = $this->photoRepository->getById($request->get('photo_id'));

        $photo->setIsPublishedAttribute(true);

        $this->photoRepository->save($photo, $request->all(), ['tags']);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/photo/:id Get
     * @apiName Get
     * @apiGroup Photo
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
     *
     *
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
     * @api {get} /v1/photo Get Collection
     * @apiName Get Collection
     * @apiGroup Photo
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
     *
     *
     * Find photos.
     *
     * @param FindPhoto $request
     * @return Collection
     */
    public function find(FindPhoto $request) : Collection
    {
        $photos = $this->photoRepository
            ->pushCriteria(new IsPublished)
            ->pushCriteria($request->has('tag') ? new WhereTag($request->get('tag')) : null)
            ->pushCriteria($request->has('query') ? new WhereSearchQuery($request->get('query')) : null)
            ->pushCriteria(new Skip($request->get('skip', 0)))
            ->pushCriteria(new Take($request->get('take', 10)))
            ->pushCriteria(new OrderBy('created_at', 'desc'))
            ->getAll();

        return $photos;
    }

    /**
     * @apiVersion 1.0.0
     * @api {put} /v1/photo/:id Update
     * @apiName Update
     * @apiGroup Photo
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
     *
     *
     * Update a photo.
     *
     * @param UpdatePhoto $request
     * @param Photo $photo
     * @return Photo
     * @throws Throwable
     */
    public function update(UpdatePhoto $request, $photo) : Photo
    {
        $this->photoRepository->save($photo, $request->all(), ['tags']);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/photo/:id Delete
     * @apiName Delete
     * @apiGroup Photo
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     *
     *
     * Delete a photo.
     *
     * @param Photo $photo
     * @return int
     */
    public function delete($photo) : int
    {
        return $this->photoRepository->delete($photo);
    }
}
