<?php

namespace Api\V1\Http\Controllers;

/**
 * Class PhotoController.
 *
 * @see \Api\V1\Services\PhotoService
 * @package Api\V1\Http\Controllers
 */
class PhotoController extends ResourceController
{
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
     */

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
     */

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
     */

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
     */

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
     */
}
