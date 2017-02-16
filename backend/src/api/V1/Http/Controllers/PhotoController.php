<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreateUploadedPhoto;
use Api\V1\Http\Requests\UpdateUploadedPhoto;
use Core\DAL\Models\Photo;
use Core\DAL\DataService\Photo\Contracts\PhotoDataService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class PhotoController.
 *
 * @property PhotoDataService photoDataService
 * @package Api\V1\Http\Controllers
 */
class PhotoController extends ResourceController
{
    /**
     * PhotoController constructor.
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
     * @api {post} /v1/photos Create
     * @apiName Create
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {File{1KB..20MB}=JPEG,PNG} file Photo file.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "user_id": 1,
     *          "absolute_url": "http://path/to/photo/file",
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
     *          ]
     *      }
     *  }
     */

    /**
     * Create a photo.
     *
     * @param CreateUploadedPhoto $request
     * @return Photo
     */
    public function create(CreateUploadedPhoto $request) : Photo
    {
        $photo = new Photo;

        $photo->setIsPublishedAttribute(false);

        $this->photoDataService->save($photo, $request->all(), ['exif', 'thumbnails']);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/photos/:id Get
     * @apiName Get
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "user_id": 1,
     *          "absolute_url": "http://path/to/photo/file",
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
     * @api {post} /v1/photos/:id Update
     * @apiName Update
     * @apiGroup Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type multipart/form-data
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiParam {File{1KB..20MB}=JPEG,PNG} file Photo file.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "user_id": 1,
     *          "absolute_url": "http://path/to/photo/file",
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
     *          ]
     *      }
     *
     */

    /**
     * Update a photo.
     *
     * @param UpdateUploadedPhoto $request
     * @param Photo $photo
     * @return Photo
     */
    public function update(UpdateUploadedPhoto $request, $photo) : Photo
    {
        $this->photoDataService->save($photo, $request->all(), ['exif', 'thumbnails']);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/photos/:id Delete
     * @apiName Delete
     * @apiGroup Photos
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
