<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Core\Resource\Contracts\Resource;
use Api\V1\Http\Middleware\DeletePhotoDirectory;
use Api\V1\Http\Middleware\UploadPhotoFile;
use Api\V1\Http\Middleware\CreateThumbnailFiles;
use Api\V1\Http\Resources\UploadedPhotoResource;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class UploadedPhotoController
 *
 * @see UploadedPhotoResource
 * @package Api\V1\Http\Controllers
 */
class UploadedPhotoController extends ResourceController
{
    /**
     * @inheritdoc
     */
    public function __construct(Request $request, Guard $guard, Resource $resource, $presenter)
    {
        parent::__construct($request, $guard, $resource, $presenter);

        $this->middleware(UploadPhotoFile::class, ['only' => ['create', 'update']]);
        $this->middleware(CreateThumbnailFiles::class, ['only' => ['create', 'update']]);
        $this->middleware(DeletePhotoDirectory::class, ['only' => ['delete']]);
    }

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
     *          "thumbnails": [
     *              {
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  "width": 500,
     *                  "height": 500
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
     *          "thumbnails": [
     *              {
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  "width": 500,
     *                  "height": 500
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
     *          "thumbnails": [
     *              {
     *                  "absolute_url": "http://path/to/photo/thumbnail/file"
     *                  "width": 500,
     *                  "height": 500
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
