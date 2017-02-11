<?php

namespace Api\V1\Http\Controllers;

/**
 * Class UploadedPhotoController.
 *
 * @see \Api\V1\Services\UploadedPhotoService
 * @package Api\V1\Http\Controllers
 */
class UploadedPhotoController extends ResourceController
{
    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/uploaded_photo Create
     * @apiName Create
     * @apiGroup Uploaded Photo
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
     * @apiVersion 1.0.0
     * @api {get} /v1/uploaded_photo/:id Get
     * @apiName Get
     * @apiGroup Uploaded Photo
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
     * @apiVersion 1.0.0
     * @api {post} /v1/uploaded_photo/:id Update
     * @apiName Update
     * @apiGroup Uploaded Photo
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
     *  }
     */

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/uploaded_photo/:id Delete
     * @apiName Delete
     * @apiGroup Uploaded Photo
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     */
}
