<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Http\Middleware\DeletePhotoDirectory;
use Api\V1\Http\Resources\PhotoResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class PhotoController
 *
 * @see PhotoResource
 * @package Api\V1\Http\Controllers
 */
class PhotoController extends ResourceController
{
    /**
     * @inheritdoc
     */
    public function __construct(Request $request, Guard $guard, Resource $resource, $presenter)
    {
        parent::__construct($request, $guard, $resource, $presenter);

        $this->middleware(DeletePhotoDirectory::class, ['only' => ['delete']]);
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
     *          "thumbnails": [
     *              {
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  "width": 500,
     *                  "height": 500
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
     *          "thumbnails": [
     *              {
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  "width": 500,
     *                  "height": 500
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
     *              "thumbnails": [
     *                  {
     *                      "absolute_url": "http://path/to/photo/thumbnail/file"
     *                      "width": 500,
     *                      "height": 500
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
     *          "thumbnails": [
     *              {
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  "width": 500,
     *                  "height": 500
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
