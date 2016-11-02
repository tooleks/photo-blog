<?php

namespace App\Http\Controllers;

use App\Http\Requests\Photos\AllPhotosRequest;
use App\Http\Requests\Photos\SearchPhotosRequest;
use App\Http\Requests\Photos\UploadPhotoRequest;
use App\Models\Photo;
use App\Services\Photos\Contracts\PhotoServiceContract;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class PhotoController
 * @property PhotoServiceContract $photoService
 * @package App\Http\Controllers
 */
class PhotoController extends Controller
{
    /**
     * PhotoController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param PhotoServiceContract $photoService
     */
    public function __construct(Request $request, Guard $guard, PhotoServiceContract $photoService)
    {
        parent::__construct($request, $guard);

        $this->photoService = $photoService;
    }

    /**
     * Get photo.
     *
     * @apiVersion 1.0.0
     * @api {get} photo/:id Get Photo
     * @apiName GetPhoto
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "is_uploaded": true,
     *          "absolute_url": "http://path/to/photo/file",
     *          "tags": [
     *              {
     *                  "id": 1,
     *                  "text": "lorem"
     *              },
     *              {
     *                  "id": 2,
     *                  "text": "ipsum"
     *              }
     *          ],
     *          "thumbnails": [
     *              {
     *                  "id": 1,
     *                  "width": 500,
     *                  "height": 500,
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *              }
     *          ]
     *      }
     *  }
     *
     * @param Photo $photo
     * @return Photo
     */
    public function view(Photo $photo)
    {
        return $photo;
    }

    /**
     * Create photo.
     *
     * @apiVersion 1.0.0
     * @api {post} photo Create Photo
     * @apiName CreatePhoto
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..65535}} description Description.
     * @apiParam {Object[]} tags Tags.
     * @apiParam {String{1..255}} tags.text Tag text.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "is_uploaded": true,
     *          "absolute_url": "http://path/to/photo/file",
     *          "tags": [
     *              {
     *                  "id": 1,
     *                  "text": "lorem"
     *              },
     *              {
     *                  "id": 2,
     *                  "text": "ipsum"
     *              }
     *          ],
     *          "thumbnails": []
     *      }
     *  }
     *
     * @return Photo
     */
    public function create()
    {
        $photo = $this->photoService
            ->setScenario('create')
            ->create($this->request->all());

        return $photo;
    }

    /**
     * Update photo.
     *
     * @apiVersion 1.0.0
     * @api {put} photo/:id Update Photo
     * @apiName UpdatePhoto
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {Integer{1..N}} :id Unique ID.
     * @apiParam {String{1..65535}} description Description.
     * @apiParam {Object[]} tags Tags.
     * @apiParam {String{1..255}} tags.text Tag text.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "is_uploaded": true,
     *          "absolute_url": "http://path/to/photo/file",
     *          "tags": [
     *              {
     *                  "id": 1,
     *                  "text": "lorem"
     *              },
     *              {
     *                  "id": 2,
     *                  "text": "ipsum"
     *              }
     *          ],
     *          "thumbnails": [
     *              {
     *                  "id": 1,
     *                  "width": 500,
     *                  "height": 500,
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *              }
     *          ]
     *      }
     *  }
     *
     * @param Photo $photo
     * @return Photo
     */
    public function update(Photo $photo)
    {
        $photo = $this->photoService
            ->setScenario('update')
            ->save($photo, $this->request->all());

        return $photo;
    }

    /**
     * Upload photo file.
     *
     * @apiVersion 1.0.0
     * @api {post} photo/:id/upload Upload Photo File
     * @apiName UploadPhotoFile
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {Integer{1..N}} [:id] Unique ID.
     * @apiParam {File{1KB..20MB}} file File.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "is_uploaded": true,
     *          "absolute_url": "http://path/to/photo/file",
     *          "tags": [
     *              {
     *                  "id": 1,
     *                  "text": "lorem"
     *              },
     *              {
     *                  "id": 2,
     *                  "text": "ipsum"
     *              }
     *          ],
     *          "thumbnails": [
     *              {
     *                  "id": 1,
     *                  "width": 500,
     *                  "height": 500,
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *              }
     *          ]
     *      }
     *  }
     *
     * @param UploadPhotoRequest $request
     * @param Photo $photo
     * @return Photo
     */
    public function upload(UploadPhotoRequest $request, Photo $photo)
    {
        if (!$photo->exists) {
            $photo = $this->photoService->create([], false);
        }

        $photo = $this->photoService
            ->setScenario('upload')
            ->save($photo, $request->all());

        return $photo;
    }

    /**
     * Delete photo.
     *
     * @apiVersion 1.0.0
     * @api {delete} photo/:id Delete Photo
     * @apiName DeletePhoto
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {Integer{1..N}} :id Unique ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     *
     * @param Photo $photo
     * @return bool
     */
    public function delete(Photo $photo)
    {
        $result = $this->photoService->delete($photo);

        return $result;
    }

    /**
     * Get photos.
     *
     * @apiVersion 1.0.0
     * @api {get} photos Get Photos
     * @apiName GetPhotos
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer} take Limit number.
     * @apiParam {Integer} skip Offset number.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": [
     *          {
     *              "id": 1,
     *              "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *              "created_at": "2016-10-24 12:24:33",
     *              "updated_at": "2016-10-24 14:38:05",
     *              "is_uploaded": true,
     *              "absolute_url": "http://path/to/photo/file",
     *              "tags": [
     *                  {
     *                      "id": 1,
     *                      "text": "lorem"
     *                  },
     *                  {
     *                      "id": 2,
     *                      "text": "ipsum"
     *                  }
     *              ],
     *              "thumbnails": [
     *                  {
     *                      "id": 1,
     *                      "width": 500,
     *                      "height": 500,
     *                      "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  }
     *              ]
     *          }
     *      ]
     *  }
     *
     * @param AllPhotosRequest $request
     * @return Collection
     */
    public function all(AllPhotosRequest $request)
    {
        $photos = $this->photoService->get($request->query('take'), $request->query('skip'));

        return $photos;
    }

    /**
     * Get photos by search criteria.
     *
     * @apiVersion 1.0.0
     * @api {get} photos/search Get Photos By Criteria
     * @apiName GetPhotosByCriteria
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer} take Limit number.
     * @apiParam {Integer} skip Offset number.
     * @apiParam {String} [tag] Tag query.
     * @apiParam {String} [query] Search query.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": [
     *          {
     *              "id": 1,
     *              "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
     *              "created_at": "2016-10-24 12:24:33",
     *              "updated_at": "2016-10-24 14:38:05",
     *              "is_uploaded": true,
     *              "absolute_url": "http://path/to/photo/file",
     *              "tags": [
     *                  {
     *                      "id": 1,
     *                      "text": "lorem"
     *                  },
     *                  {
     *                      "id": 2,
     *                      "text": "ipsum"
     *                  }
     *              ],
     *              "thumbnails": [
     *                  {
     *                      "id": 1,
     *                      "width": 500,
     *                      "height": 500,
     *                      "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  }
     *              ]
     *          }
     *      ]
     *  }
     *
     * @param SearchPhotosRequest $request
     * @return Collection
     */
    public function search(SearchPhotosRequest $request)
    {
        $photos = $this->photoService
            ->setScenario('search')
            ->getBySearchParameters(
                $request->query('take'),
                $request->query('skip'),
                $request->query()
            );

        return $photos;
    }
}
